<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRecommendation;
use App\Models\ClassSchedule;
use App\Models\CoachNote;
use App\Models\Development;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalAttendanceToday = Attendance::where('recorded_by', $userId)
            ->whereDate('attendance_date', today())
            ->count();

        $totalAttendance = Attendance::where('recorded_by', $userId)->count();

        $totalDevelopments = Development::where('coach_id', $userId)->count();

        $totalRecommendations = ClassRecommendation::where('from_user_id', $userId)->count();

        $recentAttendances = Attendance::where('recorded_by', $userId)
            ->with('student', 'schoolClass')
            ->latest('attendance_date')
            ->latest('id')
            ->take(8)
            ->get();

        $notes = CoachNote::where('coach_id', $userId)
            ->latest('note_date')
            ->latest('id')
            ->get();

        $todayDay = ClassSchedule::DAYS[(now()->dayOfWeek + 6) % 7];
        $todaySchedules = ClassSchedule::with(['schoolClass.program', 'students'])
            ->where('day', $todayDay)
            ->whereHas('coaches', fn ($q) => $q->where('users.id', $userId))
            ->orderBy('start_time')
            ->get();

        $todayStudentCount = $todaySchedules->flatMap(fn ($s) => $s->students)->unique('id')->count();

        $sortedSchedules = ClassSchedule::with(['schoolClass.program', 'students'])
            ->whereHas('coaches', fn ($q) => $q->where('users.id', $userId))
            ->get()
            ->map(function ($schedule) {
                $dayIndex = array_search($schedule->day, ClassSchedule::DAYS);
                $daysAhead = ($dayIndex + 1) - now()->dayOfWeekIso;
                if ($daysAhead < 0) {
                    $daysAhead += 7;
                }
                $occurrence = now()->copy()->addDays($daysAhead)
                    ->setTimeFromTimeString($schedule->start_time ?? '00:00');
                if ($occurrence->isBefore(now())) {
                    $occurrence->addWeek();
                }
                $schedule->next_occurrence = $occurrence;

                return $schedule;
            })
            ->sortBy('next_occurrence')
            ->values();

        $upcomingSchedules = $sortedSchedules->filter(function ($s) {
            return $s->next_occurrence->lte(now()->copy()->endOfDay()->addDay());
        })->values();
        $nextSchedule = $sortedSchedules->first();

        $days = collect(range(6, 0))->map(function ($offset) {
            return today()->subDays($offset);
        });

        $attendanceChart = $days->map(function (Carbon $day) use ($userId) {
            return [
                'label' => $day->format('d/m'),
                'total' => Attendance::where('recorded_by', $userId)->whereDate('attendance_date', $day)->count(),
                'students' => Attendance::where('recorded_by', $userId)->whereDate('attendance_date', $day)->distinct('student_id')->count('student_id'),
            ];
        });

        $canAssess = auth()->user()->canAssessDevelopments();

        return view('pelatih.dashboard', compact(
            'totalAttendanceToday',
            'totalAttendance',
            'totalDevelopments',
            'totalRecommendations',
            'recentAttendances',
            'notes',
            'todayDay',
            'todaySchedules',
            'todayStudentCount',
            'nextSchedule',
            'upcomingSchedules',
            'attendanceChart',
            'canAssess'
        ));
    }
}
