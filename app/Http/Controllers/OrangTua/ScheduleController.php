<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $children = auth()->user()->students()
            ->orderBy('full_name')
            ->get()
            ->map(function ($child) {
                $child->load(['classes' => fn ($q) => $q->wherePivot('is_active', true)]);

                $activeClassIds = $child->classes->pluck('id');

                $childSchedules = $child->schedules()
                    ->with(['schoolClass.program', 'coaches'])
                    ->whereIn('class_schedules.class_id', $activeClassIds)
                    ->get();

                $schedulesByDay = collect(ClassSchedule::DAYS)->mapWithKeys(fn ($day) => [
                    $day => $childSchedules->where('day', $day)->sortBy('start_time')->values(),
                ]);

                return [
                    'child' => $child,
                    'schedulesByDay' => $schedulesByDay,
                ];
            })
            ->values();

        return view('orangtua.schedules.index', ['children' => $children]);
    }
}
