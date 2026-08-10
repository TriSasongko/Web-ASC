<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

        $this->validateLevelProgram($validated['program_id'], $validated['level']);
        $this->validateUniqueClass($validated['program_id'], $validated['level']);

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

        $this->validateLevelProgram($validated['program_id'], $validated['level']);
        $this->validateUniqueClass($validated['program_id'], $validated['level'], $class);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    private function validateUniqueClass(int $programId, int $level, ?SchoolClass $except = null): void
    {
        $exists = SchoolClass::where('program_id', $programId)
            ->where('level', $level)
            ->when($except, fn ($q) => $q->where('id', '!=', $except->id))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'level' => 'Level '.($this->levelLabel($level) ?? $level).' pada program ini sudah memiliki kelas.',
            ]);
        }
    }

    private function levelLabel(int $level): string
    {
        return SchoolClass::levelOptions()[$level] ?? 'tersebut';
    }

    private function validateLevelProgram(int $programId, int $level): void
    {
        $program = Program::findOrFail($programId);

        if (! SchoolClass::programAllowsLevel($program, $level)) {
            $rule = $program->isKompetitif()
                ? 'Kelas Kompetitif hanya boleh berisi level Advance atau Elite.'
                : 'Program '.$program->name.' hanya boleh memiliki kelas level Beginner.';

            throw ValidationException::withMessages(['level' => $rule]);
        }
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    public function show(SchoolClass $class)
    {
        $class->load(['program', 'schedules.students', 'schedules.coaches']);

        $enrollments = ClassStudent::with(['student.parent', 'schoolClass.program'])
            ->where('class_id', $class->id)
            ->where('renewal_status', '!=', 'berhenti')
            ->orderBy('is_active', 'desc')
            ->orderBy('student_id')
            ->get()
            ->filter(fn ($e) => $e->student !== null)
            ->values();

        $candidateClasses = SchoolClass::where('is_active', true)
            ->where('id', '!=', $class->id)
            ->where(function ($q) use ($class) {
                $q->where('program_id', $class->program_id)
                    ->orWhereHas('program', fn ($p) => $p->where('is_kompetitif', true));
            })
            ->when($class->level !== null, fn ($q) => $q->where('level', '>', $class->level))
            ->orderBy('level')
            ->get();

        return view('admin.classes.show', compact('class', 'enrollments', 'candidateClasses'));
    }
}
