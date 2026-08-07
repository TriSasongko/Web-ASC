<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function store(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'day' => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'session_number' => ['required', 'integer', 'min:1'],
        ]);

        $validated['class_id'] = $class->id;

        ClassSchedule::create($validated);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(ClassSchedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
