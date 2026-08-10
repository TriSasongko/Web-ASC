<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = ClassSchedule::with(['schoolClass.program', 'students', 'coaches'])
            ->whereHas('coaches', fn ($q) => $q->where('users.id', auth()->id()))
            ->get();

        $schedulesByDay = collect(ClassSchedule::DAYS)->mapWithKeys(fn ($day) => [
            $day => $schedules->where('day', $day)->sortBy('start_time')->values(),
        ]);

        return view('pelatih.schedules.index', ['schedulesByDay' => $schedulesByDay]);
    }
}
