<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $recordedCount = Attendance::where('recorded_by', auth()->id())->count();

        return view('pelatih.attendances.index', compact('recordedCount'));
    }

    public function create()
    {
        $classes = SchoolClass::with('program')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $students = Student::with(['classes' => function ($q) {
            $q->wherePivot('is_active', true)->with('program');
        }])
            ->whereHas('enrollments', fn ($q) => $q->where('is_active', true))
            ->orderBy('full_name')
            ->get();

        return view('pelatih.attendances.create', compact('classes', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'attendance' => ['required', 'array'],
            'attendance.*' => ['integer', 'exists:students,id'],
        ]);

        $validated['session_number'] ??= 1;

        DB::transaction(function () use ($validated) {
            foreach ($validated['attendance'] as $studentId) {
                $exists = Attendance::where('student_id', $studentId)
                    ->where('attendance_date', $validated['attendance_date'])
                    ->where('session_number', $validated['session_number'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                $activeEnrollment = ClassStudent::with('schoolClass.program')
                    ->where('student_id', $studentId)
                    ->where('is_active', true)
                    ->first();

                Attendance::create([
                    'class_id' => $activeEnrollment?->class_id,
                    'student_id' => $studentId,
                    'recorded_by' => auth()->id(),
                    'attendance_date' => $validated['attendance_date'],
                    'session_number' => $validated['session_number'],
                    'location' => $validated['location'] ?? null,
                ]);

                if ($activeEnrollment?->schoolClass?->program?->billing_type === 'per_paket') {
                    $program = $activeEnrollment->schoolClass->program;

                    if ($program->total_sessions && $activeEnrollment->sessions_completed >= $program->total_sessions) {
                        continue;
                    }

                    $activeEnrollment->increment('sessions_completed');
                }
            }
        });

        return redirect()->route('pelatih.attendances.history')
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function history(Request $request)
    {
        $perPage = in_array($request->integer('per_page'), [5, 10, 25, 50], true)
            ? $request->integer('per_page')
            : 10;

        $attendances = Attendance::with(['student', 'recorder', 'schoolClass'])
            ->where('recorded_by', auth()->id())
            ->when($request->student_name, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('full_name', 'like', '%'.$request->student_name.'%')))
            ->when($request->date_from, fn ($q) => $q->whereDate('attendance_date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('attendance_date', '<=', $request->date_to))
            ->orderByDesc('attendance_date')
            ->paginate($perPage)
            ->withQueryString();

        return view('pelatih.attendances.history', compact('attendances'));
    }
}
