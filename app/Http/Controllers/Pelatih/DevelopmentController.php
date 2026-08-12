<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    private function ensureCanAssess(): void
    {
        abort_if(! auth()->user()->canAssessDevelopments(), 403, 'Izin untuk mengisi penilaian belum diberikan.');
    }

    // Daftar seluruh siswa aktif dikelompokkan per level, untuk dipilih isi perkembangannya
    public function index()
    {
        $this->ensureCanAssess();

        $students = Student::with([
            'classes' => function ($q) {
                $q->wherePivot('is_active', true)->with('program');
            },
            'recommendations' => function ($q) {
                $q->where('status', 'pending');
            },
        ])
            ->whereHas('enrollments', fn ($q) => $q->where('is_active', true))
            ->orderBy('full_name')
            ->get();

        $levels = SchoolClass::levelOptions();

        return view('pelatih.developments.index', compact('students', 'levels'));
    }

    public function create(SchoolClass $class, Student $student)
    {
        $this->ensureCanAssess();

        return view('pelatih.developments.create', compact('class', 'student'));
    }

    public function store(Request $request, SchoolClass $class, Student $student)
    {
        $this->ensureCanAssess();

        $rules = ['period' => ['required', 'string', 'max:255']];
        foreach (Development::aspects() as $key => $label) {
            $rules[$key] = ['required', 'in:kurang,cukup,baik,sangat_baik'];
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

        return redirect()->route('pelatih.developments.index')
            ->with('success', 'Perkembangan siswa berhasil disimpan.');
    }

    // Riwayat semua periode penilaian untuk 1 siswa
    public function history(SchoolClass $class, Student $student)
    {
        $developments = Development::where('class_id', $class->id)
            ->where('student_id', $student->id)
            ->latest('id')
            ->get();

        return view('pelatih.developments.history', compact('class', 'student', 'developments'));
    }
}
