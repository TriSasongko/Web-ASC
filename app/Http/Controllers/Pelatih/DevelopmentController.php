<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    // Daftar siswa di kelas yang diampu, untuk dipilih isi perkembangannya
    public function index(SchoolClass $class)
    {
        abort_unless($class->coach_id === auth()->id(), 403);

        $students = $class->students()->wherePivot('is_active', true)->get();

        $candidateClasses = SchoolClass::where('is_active', true)
            ->where('program_id', $class->program_id)
            ->when($class->level, fn ($q) => $q->where('level', '>', $class->level))
            ->orderBy('level')
            ->get();

        $availableLevels = array_filter(
            SchoolClass::levelOptions(),
            fn ($label, $level) => $class->level === null || $level > $class->level,
            ARRAY_FILTER_USE_BOTH
        );

        return view('pelatih.developments.index', compact('class', 'students', 'candidateClasses', 'availableLevels'));
    }

    public function create(SchoolClass $class, Student $student)
    {
        abort_unless($class->coach_id === auth()->id(), 403);

        return view('pelatih.developments.create', compact('class', 'student'));
    }

    public function store(Request $request, SchoolClass $class, Student $student)
    {
        abort_unless($class->coach_id === auth()->id(), 403);

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

        return redirect()->route('pelatih.developments.index', $class)
            ->with('success', 'Perkembangan siswa berhasil disimpan.');
    }

    // Riwayat semua periode penilaian untuk 1 siswa
    public function history(SchoolClass $class, Student $student)
    {
        abort_unless($class->coach_id === auth()->id(), 403);

        $developments = Development::where('class_id', $class->id)
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return view('pelatih.developments.history', compact('class', 'student', 'developments'));
    }
}
