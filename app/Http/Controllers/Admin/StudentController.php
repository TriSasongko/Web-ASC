<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

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
            $records = $attendances[$class->id] ?? collect();
            $total = $class->program->total_sessions;

            if ($total === null) {
                // Billing bulanan (Kompetitif): tampilkan absensi bulan berjalan
                $records = $records
                    ->filter(fn ($r) => $r->attendance_date->between(now()->startOfMonth(), now()->endOfMonth()))
                    ->sortBy('attendance_date')
                    ->values();
            } else {
                // Billing per paket: tampilkan sesuai total sesi paket (mis. 8 atau 4)
                $records = $records
                    ->sortByDesc('attendance_date')
                    ->take($total)
                    ->sortBy('attendance_date')
                    ->values();
            }

            $attendanceLists[$class->id] = $records;
        }

        return view('admin.students.show', compact('student', 'attendanceLists'));
    }
}
