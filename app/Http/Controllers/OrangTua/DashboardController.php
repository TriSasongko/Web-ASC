<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;
use App\Models\ClassSchedule;
use App\Models\Development;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Anak dengan kelas aktif + program
        $students = $user->students()
            ->with(['classes' => fn ($q) => $q->wherePivot('is_active', true)->with('program')])
            ->orderBy('full_name')
            ->get();

        $activeClassIds = $students->flatMap(fn ($s) => $s->classes->pluck('id'))->unique()->values();
        $totalChildren = $students->count();

        // Pendaftaran (untuk panduan orang tua baru / menunggu verifikasi)
        $registrations = Registration::whereHas('student', fn ($q) => $q->where('parent_id', $user->id))
            ->with(['student', 'program'])
            ->latest()
            ->get();

        $latestRegistration = $registrations->first();

        $activePrograms = 0;
        $totalSessionsLeft = 0;

        foreach ($students as $student) {
            foreach ($student->classes as $enrollment) {
                $activePrograms++;

                $total = $enrollment->program->total_sessions;
                if ($total !== null) {
                    $totalSessionsLeft += max(0, $total - $enrollment->pivot->sessions_completed);
                }
            }
        }

        // Rekomendasi naik kelas
        $recommendations = ClassRecommendation::with(['student', 'recommendedClass', 'currentClass', 'from'])
            ->whereHas('student', fn ($q) => $q->where('parent_id', $user->id))
            ->latest()
            ->get();

        $pendingRecommendations = $recommendations->where('status', 'menunggu_ortu')->count();

        // Jadwal latihan hari ini untuk anak
        $todayDay = ClassSchedule::DAYS[(now()->dayOfWeek + 6) % 7];

        $todaySchedules = ClassSchedule::with(['schoolClass.program', 'coaches'])
            ->where('day', $todayDay)
            ->whereIn('class_id', $activeClassIds)
            ->orderBy('start_time')
            ->get();

        // Jadwal 7 hari ke depan (tanggal sebenarnya untuk setiap sesi)
        $upcomingSchedules = collect();

        for ($offset = 0; $offset < 7; $offset++) {
            $date = today()->addDays($offset);
            $day = ClassSchedule::DAYS[($date->dayOfWeek + 6) % 7];

            ClassSchedule::with(['schoolClass.program', 'coaches'])
                ->where('day', $day)
                ->whereIn('class_id', $activeClassIds)
                ->orderBy('start_time')
                ->get()
                ->each(function ($schedule) use (&$upcomingSchedules, $date) {
                    $upcomingSchedules->push([
                        'date' => $date,
                        'schedule' => $schedule,
                    ]);
                });
        }

        $upcomingSchedules = $upcomingSchedules
            ->sortBy(fn ($item) => $item['date']->toDateString().' '.($item['schedule']->start_time ?? ''))
            ->take(5)
            ->values();

        // E-Raport terbaru per anak
        $latestDevelopments = Development::whereHas('student', fn ($q) => $q->where('parent_id', $user->id))
            ->with('student')
            ->orderByDesc('id')
            ->get()
            ->unique('student_id')
            ->values();

        // Rekap absensi per anak per enrollment aktif
        $attendanceRecaps = $students->map(function ($student) {
            $enrollments = $student->enrollments()
                ->where('is_active', true)
                ->with('schoolClass.program')
                ->get()
                ->map(function ($enrollment) {
                    $totalSessions = $enrollment->schoolClass?->program?->total_sessions;
                    $hadirCount = $enrollment->attendances()->count();

                    $recentAttendances = $enrollment->attendances()
                        ->orderByDesc('attendance_date')
                        ->limit(5)
                        ->get()
                        ->map(fn ($a) => [
                            'date' => $a->attendance_date->format('d M Y'),
                        ]);

                    return [
                        'class_name' => $enrollment->schoolClass?->name ?? 'Tanpa Kelas',
                        'program_name' => $enrollment->schoolClass?->program?->name ?? '-',
                        'total_sessions' => $totalSessions,
                        'hadir_count' => $hadirCount,
                        'recent_attendances' => $recentAttendances,
                    ];
                });

            return [
                'student_id' => $student->id,
                'student_name' => $student->full_name,
                'enrollments' => $enrollments,
            ];
        })->values();

        // Distribusi penilaian umum terbaru per anak (untuk diagram pie)
        $scoreWeights = array_flip(array_keys(Development::scores()));
        $scoreColors = [
            'kurang' => '#C62828',
            'cukup' => '#FFB300',
            'baik' => '#0B5ED7',
            'sangat_baik' => '#2E7D32',
        ];

        $developmentCharts = $students->map(function ($student) use ($latestDevelopments, $scoreWeights, $scoreColors) {
            $development = $latestDevelopments->firstWhere('student_id', $student->id);

            $distribution = array_fill_keys(array_keys(Development::scores()), 0);
            $total = 0;
            $weightSum = 0;

            if ($development) {
                foreach (array_keys(Development::umumAspects()) as $aspect) {
                    $value = $development->{$aspect};
                    if ($value !== null && array_key_exists($value, $distribution)) {
                        $distribution[$value]++;
                        $total++;
                        $weightSum += $scoreWeights[$value] + 1;
                    }
                }
            }

            $slices = [];
            foreach ($distribution as $key => $count) {
                if ($count > 0) {
                    $slices[] = [
                        'key' => $key,
                        'label' => Development::scoreLabel($key),
                        'count' => $count,
                        'color' => $scoreColors[$key],
                    ];
                }
            }

            $aspects = [];
            foreach (Development::umumAspects() as $key => $label) {
                $value = $development?->{$key};
                $aspects[] = [
                    'label' => $label,
                    'value' => $value,
                    'score' => Development::scoreLabel($value),
                    'color' => $value !== null ? ($scoreColors[$value] ?? null) : null,
                ];
            }

            $aspects = [];
            foreach (Development::umumAspects() as $key => $label) {
                $value = $development?->{$key};
                $aspects[] = [
                    'label' => $label,
                    'value' => $value,
                    'score' => Development::scoreLabel($value),
                    'color' => $value !== null ? ($scoreColors[$value] ?? null) : null,
                ];
            }

            return [
                'student_id' => $student->id,
                'student_name' => $student->full_name,
                'development_id' => $development?->id,
                'period' => $development?->period,
                'distribution' => $distribution,
                'slices' => $slices,
                'total' => $total,
                'average' => $total > 0 ? round($weightSum / $total, 1) : null,
            ];
        })->values();

        return view('orangtua.dashboard', compact(
            'students',
            'totalChildren',
            'activePrograms',
            'totalSessionsLeft',
            'recommendations',
            'pendingRecommendations',
            'registrations',
            'latestRegistration',
            'todayDay',
            'todaySchedules',
            'upcomingSchedules',
            'developmentCharts',
            'latestDevelopments',
            'attendanceRecaps',
        ));
    }
}
