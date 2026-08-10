<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index()
    {
        $schedules = ClassSchedule::with(['schoolClass.program', 'schoolClass.students', 'students', 'coaches'])
            ->whereHas('schoolClass', fn ($q) => $q->where('is_active', true))
            ->get();

        $schedulesByDay = collect(ClassSchedule::DAYS)->mapWithKeys(fn ($day) => [
            $day => $schedules->where('day', $day)->sortBy('start_time')->values(),
        ]);

        $classes = SchoolClass::with(['program', 'students'])->where('is_active', true)->orderBy('name')->get();
        $coaches = User::where('role', 'pelatih')->where('is_active', true)->orderBy('name')->get();

        $studentsByClass = $classes->flatMap(fn ($c) => $c->students
            ->where('pivot.is_active', true)
            ->map(fn ($st) => [
                'id' => $st->id,
                'class_id' => $c->id,
                'name' => $st->full_name,
            ]))
            ->values();

        return view('admin.schedules.index', compact('schedulesByDay', 'classes', 'coaches', 'studentsByClass'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'day' => ['required', 'in:'.implode(',', ClassSchedule::DAYS)],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'session_number' => ['required', 'integer', 'min:1'],
            'coach_ids' => ['nullable', 'array'],
            'coach_ids.*' => ['integer', 'exists:users,id'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:students,id'],
        ]);

        $class = SchoolClass::findOrFail($validated['class_id']);

        $schedule = ClassSchedule::create([
            'class_id' => $class->id,
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'] ?? null,
            'session_number' => $validated['session_number'],
        ]);

        $schedule->coaches()->sync($validated['coach_ids'] ?? []);

        // Hanya siswa yang memang terdaftar aktif di kelas ini yang disimpan ke sesi
        $studentIds = collect($validated['student_ids'] ?? [])
            ->filter(fn ($id) => $class->students()
                ->where('students.id', $id)
                ->wherePivot('is_active', true)
                ->exists())
            ->values();

        $schedule->students()->sync($studentIds);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function assign(Request $request, ClassSchedule $schedule)
    {
        $validated = $request->validate([
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:students,id'],
            'coach_ids' => ['nullable', 'array'],
            'coach_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $schedule->students()->sync($validated['student_ids'] ?? []);
        $schedule->coaches()->sync($validated['coach_ids'] ?? []);

        return back()->with('success', 'Siswa dan pelatih jadwal berhasil diperbarui.');
    }

    public function destroy(ClassSchedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
