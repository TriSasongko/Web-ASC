<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    public function index(Request $request)
    {
        $developments = Development::with(['student', 'schoolClass.program', 'coach'])
            ->when($request->search, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('full_name', 'like', '%'.$request->search.'%')))
            ->latest()
            ->paginate(15);

        return view('admin.developments.index', compact('developments'));
    }

    // Daftar siswa di kelas, untuk dipilih isi perkembangannya (akses penuh admin)
    public function classIndex(SchoolClass $class)
    {
        $students = $class->students()->wherePivot('is_active', true)->get();

        return view('admin.developments.class-index', compact('class', 'students'));
    }

    public function create(SchoolClass $class, Student $student)
    {
        return view('admin.developments.create', compact('class', 'student'));
    }

    public function store(Request $request, SchoolClass $class, Student $student)
    {
        $rules = ['period' => ['required', 'string', 'max:255']];
        foreach (Development::aspects() as $key => $label) {
            $rules[$key] = ['required', 'in:belum,cukup,baik,sangat_baik'];
        }
        $rules['coach_note'] = ['nullable', 'string'];

        $validated = $request->validate($rules);
        $validated['class_id'] = $class->id;
        $validated['student_id'] = $student->id;
        $validated['coach_id'] = auth()->id();

        Development::updateOrCreate(
            ['class_id' => $class->id, 'student_id' => $student->id, 'period' => $validated['period']],
            $validated
        );

        return redirect()->route('admin.classes.developments.index', $class)
            ->with('success', 'Perkembangan siswa berhasil disimpan.');
    }

    // Riwayat semua periode penilaian untuk 1 siswa
    public function history(SchoolClass $class, Student $student)
    {
        $developments = Development::where('class_id', $class->id)
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return view('admin.developments.history', compact('class', 'student', 'developments'));
    }

    public function destroy(Development $development)
    {
        $development->delete();

        return back()->with('success', 'Data perkembangan berhasil dihapus.');
    }
}
