<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Development;
use App\Models\SchoolClass;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class ERaportController extends Controller
{
    public function show(Student $student, $developmentId)
    {
        $this->authorizeAccess($student);

        $development = Development::with(['schoolClass.program', 'coach', 'schoolClass.schedules'])
            ->findOrFail($developmentId);

        $data = $this->reportData($student, $development);

        return view('eraport.show', compact('student', 'development') + $data);
    }

    public function downloadPdf(Student $student, $developmentId)
    {
        $this->authorizeAccess($student);

        $development = Development::with(['schoolClass.program', 'coach', 'schoolClass.schedules'])
            ->findOrFail($developmentId);

        $data = $this->reportData($student, $development);

        $pdf = Pdf::loadView('eraport.pdf', compact('student', 'development') + $data);

        return $pdf->download('E-Raport-'.str_replace(' ', '-', $student->full_name).'-'.$development->period.'.pdf');
    }

    // Data pelengkap untuk halaman & PDF e-raport
    private function reportData(Student $student, Development $development): array
    {
        $attendanceCount = Attendance::where('class_id', $development->class_id)
            ->where('student_id', $student->id)
            ->count();

        $program = $development->schoolClass?->program;
        $totalSessions = $program?->total_sessions;
        $attendancePercent = $totalSessions ? (int) round($attendanceCount / $totalSessions * 100) : null;

        $studentLevel = $development->schoolClass?->level;

        return [
            'attendanceCount' => $attendanceCount,
            'totalSessions' => $totalSessions,
            'attendancePercent' => $attendancePercent,
            'studentLevel' => SchoolClass::levelLabel($studentLevel),
            'scheduleLabel' => $this->scheduleLabel($development->schoolClass->schedules),
            'overallScore' => $this->overallScore($development),
            'radarData' => $this->radarData($development),
            'trendData' => $this->trendData($student, $development),
        ];
    }

    // Data radar: rata-rata per dimensi (umum + tiap gaya renang)
    private function radarData(Development $development): array
    {
        $dimensions = [];

        $umum = collect(Development::umumAspects())
            ->map(fn ($label, $key) => $this->scoreNumber($development->{$key}))
            ->filter()
            ->avg();

        if ($umum !== null) {
            $dimensions['Penilaian Umum'] = (int) round($umum);
        }

        foreach (Development::styles() as $style => $styleLabel) {
            $avg = collect(Development::khususAspects())
                ->map(fn ($label, $key) => $this->scoreNumber($development->{$style.'_'.$key}))
                ->filter()
                ->avg();

            if ($avg !== null) {
                $dimensions[$styleLabel] = (int) round($avg);
            }
        }

        $labels = array_keys($dimensions);
        $values = array_values($dimensions);

        return [
            'labels' => $labels,
            'values' => $values,
            'keys' => array_map(fn ($value) => $this->nearestScoreKey($value), $values),
        ];
    }

    // Konversi angka rata-rata (0-100) ke kunci skor terdekat (kurang/cukup/baik/sangat_baik)
    private function nearestScoreKey(int $value): string
    {
        $thresholds = [
            'kurang' => 25,
            'cukup' => 50,
            'baik' => 75,
            'sangat_baik' => 100,
        ];

        $closest = 'cukup';
        $distance = PHP_INT_MAX;

        foreach ($thresholds as $key => $threshold) {
            $current = abs($value - $threshold);

            if ($current < $distance) {
                $distance = $current;
                $closest = $key;
            }
        }

        return $closest;
    }

    // Data tren: skor keseluruhan tiap periode untuk siswa di kelas yang sama
    private function trendData(Student $student, Development $development): array
    {
        $history = Development::where('student_id', $student->id)
            ->where('class_id', $development->class_id)
            ->orderBy('id')
            ->get();

        return [
            'labels' => $history->pluck('period')->all(),
            'values' => $history->map(fn ($item) => $this->overallScoreNumber($item))->all(),
        ];
    }

    private function overallScoreNumber(Development $development): ?int
    {
        $scores = collect(Development::aspects())
            ->map(fn ($label, $key) => $this->scoreNumber($development->{$key}))
            ->filter();

        return $scores->isEmpty() ? null : (int) round($scores->avg());
    }

    // Konversi skor teks ke angka untuk grafik (25/50/75/100)
    private function scoreNumber(?string $value): ?int
    {
        return match ($value) {
            'kurang' => 25,
            'cukup' => 50,
            'baik' => 75,
            'sangat_baik' => 100,
            default => null,
        };
    }

    private function scheduleLabel($schedules): string
    {
        return $schedules->map(function ($schedule) {
            $start = $schedule->start_time ? substr($schedule->start_time, 0, 5) : '';
            $end = $schedule->end_time ? substr($schedule->end_time, 0, 5) : '';

            return ucfirst($schedule->day).' '.$start.'–'.$end;
        })->join(', ');
    }

    private function overallScore(Development $development): array
    {
        $points = ['kurang' => 1, 'cukup' => 2, 'baik' => 3, 'sangat_baik' => 4];

        $scores = collect(Development::aspects())
            ->map(fn ($label, $key) => $development->{$key})
            ->filter(fn ($value) => isset($points[$value]));

        if ($scores->isEmpty()) {
            return ['key' => null, 'label' => '-'];
        }

        $nearest = (int) round($scores->map(fn ($value) => $points[$value])->average());
        $key = array_search($nearest, $points, true) ?: 'cukup';

        return ['key' => $key, 'label' => Development::scoreLabel($key)];
    }

    // Orang tua hanya boleh akses e-raport anaknya sendiri; admin boleh akses semua
    private function authorizeAccess(Student $student): void
    {
        $user = auth()->user();

        if ($user->role === 'orang_tua' && $student->parent_id !== $user->id) {
            abort(403);
        }
    }
}
