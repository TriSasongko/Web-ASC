<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRecommendation;
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
            ->latest()
            ->get();

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
            'attendanceChart',
            'canAssess'
        ));
    }
}
