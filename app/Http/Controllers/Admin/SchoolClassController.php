<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::with(['program', 'schedules'])
            ->when($request->program_id, fn ($q) => $q->where('program_id', $request->program_id))
            ->latest()
            ->paginate(10);

        $programs = Program::where('is_active', true)->get();

        return view('admin.classes.index', compact('classes', 'programs'));
    }

    public function create()
    {
        $programs = Program::where('is_active', true)->get();

        return view('admin.classes.create', compact('programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'integer', 'min:1', 'max:3'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ]);

        SchoolClass::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function edit(SchoolClass $class)
    {
        $programs = Program::where('is_active', true)->get();

        return view('admin.classes.edit', compact('class', 'programs'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'integer', 'min:1', 'max:3'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    public function show(SchoolClass $class)
    {
        $class->load(['program', 'schedules']);

        $enrollments = ClassStudent::with(['student.parent', 'schoolClass.program'])
            ->where('class_id', $class->id)
            ->where('renewal_status', '!=', 'berhenti')
            ->orderBy('is_active', 'desc')
            ->orderBy('student_id')
            ->get()
            ->filter(fn ($e) => $e->student !== null)
            ->values();

        $candidateClasses = SchoolClass::where('is_active', true)
            ->where('program_id', $class->program_id)
            ->when($class->level, fn ($q) => $q->where('level', '>', $class->level))
            ->orderBy('level')
            ->get();

        return view('admin.classes.show', compact('class', 'enrollments', 'candidateClasses'));
    }
}
