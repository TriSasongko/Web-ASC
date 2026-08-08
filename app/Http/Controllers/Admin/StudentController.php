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
        $students = Student::with('parent')
            ->whereHas('registrations', fn ($q) => $q->where('status', 'diterima'))
            ->when($request->search, fn ($q) => $q->where('full_name', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load(['parent', 'classes.program', 'classes.coach']);

        // Riwayat absensi siswa ini, dikelompokkan per kelas yang diikuti
        $attendances = Attendance::where('student_id', $student->id)
            ->orderByDesc('attendance_date')
            ->get()
            ->groupBy('class_id');

        return view('admin.students.show', compact('student', 'attendances'));
    }
}
