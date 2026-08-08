<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::with(['program', 'coach'])
            ->when($request->coach_id, fn($q) => $q->where('coach_id', $request->coach_id))
            ->where('is_active', true)
            ->get();

        $coaches = User::where('role', 'pelatih')->get();

        return view('admin.attendances.index', compact('classes', 'coaches'));
    }

    public function create(SchoolClass $class)
    {
        $students = $class->students()->wherePivot('is_active', true)->get();
        return view('admin.attendances.create', compact('class', 'students'));
    }

    public function store(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date'],
            'session_number' => ['required', 'integer', 'min:1'],
            'attendance' => ['required', 'array'],
            'attendance.*' => ['required', 'in:hadir,tidak_hadir'],
        ]);

        DB::transaction(function () use ($validated, $class) {
            foreach ($validated['attendance'] as $studentId => $status) {

                $exists = Attendance::where('class_id', $class->id)
                    ->where('student_id', $studentId)
                    ->where('attendance_date', $validated['attendance_date'])
                    ->where('session_number', $validated['session_number'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                Attendance::create([
                    'class_id' => $class->id,
                    'student_id' => $studentId,
                    'recorded_by' => auth()->id(),
                    'attendance_date' => $validated['attendance_date'],
                    'session_number' => $validated['session_number'],
                    'status' => $status,
                ]);

                if ($status === 'hadir' && $class->program->billing_type === 'per_paket') {
                    $class->students()->updateExistingPivot($studentId, [
                        'sessions_completed' => DB::raw('sessions_completed + 1'),
                    ]);

                    $pivot = DB::table('class_student')
                        ->where('class_id', $class->id)
                        ->where('student_id', $studentId)
                        ->first();

                    if ($class->program->total_sessions && $pivot->sessions_completed >= $class->program->total_sessions) {
                        $class->students()->updateExistingPivot($studentId, ['is_active' => false]);
                    }
                }
            }
        });

        return redirect()->route('admin.attendances.index')->with('success', 'Absensi berhasil disimpan oleh Admin.');
    }

    public function history(SchoolClass $class)
    {
        $attendances = Attendance::where('class_id', $class->id)
            ->with(['student', 'recorder'])
            ->orderByDesc('attendance_date')
            ->paginate(20);

        return view('admin.attendances.history', compact('class', 'attendances'));
    }

    public function edit(Attendance $attendance)
    {
        return view('admin.attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:hadir,tidak_hadir'],
        ]);

        $class = $attendance->schoolClass;
        $wasHadir = $attendance->status === 'hadir';
        $nowHadir = $validated['status'] === 'hadir';

        DB::transaction(function () use ($attendance, $validated, $class, $wasHadir, $nowHadir) {
            $attendance->update($validated);

            if ($class->program->billing_type === 'per_paket' && $wasHadir !== $nowHadir) {
                $adjustment = $nowHadir ? 1 : -1;
                $class->students()->updateExistingPivot($attendance->student_id, [
                    'sessions_completed' => DB::raw("GREATEST(0, sessions_completed + ({$adjustment}))"),
                ]);
            }
        });

        return redirect()->route('admin.attendances.history', $class)->with('success', 'Absensi berhasil dikoreksi.');
    }

    public function destroy(Attendance $attendance)
    {
        $class = $attendance->schoolClass;

        if ($attendance->status === 'hadir' && $class->program->billing_type === 'per_paket') {
            $class->students()->updateExistingPivot($attendance->student_id, [
                'sessions_completed' => DB::raw('GREATEST(0, sessions_completed - 1)'),
            ]);
        }

        $attendance->delete();

        return back()->with('success', 'Data absensi berhasil dihapus.');
    }
}
