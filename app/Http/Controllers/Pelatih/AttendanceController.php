<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with('program')
            ->where('coach_id', auth()->id())
            ->where('is_active', true)
            ->get();

        return view('pelatih.attendances.index', compact('classes'));
    }

    public function create(SchoolClass $class)
    {
        abort_unless($class->coach_id === auth()->id(), 403);

        $students = $class->students()->wherePivot('is_active', true)->get();

        return view('pelatih.attendances.create', compact('class', 'students'));
    }

    public function store(Request $request, SchoolClass $class)
    {
        abort_unless($class->coach_id === auth()->id(), 403);

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

        return redirect()->route('pelatih.attendances.index')
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function history(SchoolClass $class)
    {
        abort_unless($class->coach_id === auth()->id(), 403);

        $attendances = Attendance::where('class_id', $class->id)
            ->with('student')
            ->orderByDesc('attendance_date')
            ->paginate(20);

        return view('pelatih.attendances.history', compact('class', 'attendances'));
    }
}
