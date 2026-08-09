<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->integer('per_page'), [5, 10, 25, 50], true)
            ? $request->integer('per_page')
            : 10;

        $students = Student::with(['parent', 'classes.program'])
            ->activeProgram()
            ->whereHas('registrations', fn ($q) => $q->where('status', 'diterima'))
            ->when($request->search, fn ($q) => $q->where('full_name', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load(['parent', 'classes.program']);

        $attendances = Attendance::with('recorder')
            ->where('student_id', $student->id)
            ->orderBy('attendance_date')
            ->get()
            ->groupBy('class_id');

        $attendanceLists = [];

        foreach ($student->classes as $class) {
            $pivot = $class->pivot;
            $renewedAt = $pivot->renewed_at ? Carbon::parse($pivot->renewed_at) : null;

            $attendanceLists[$class->id] = ($attendances[$class->id] ?? collect())
                ->filter(fn ($r) => $renewedAt ? $r->attendance_date->gte($renewedAt) : true)
                ->sortBy('attendance_date')
                ->values();
        }

        return view('admin.students.show', compact('student', 'attendanceLists'));
    }
}
