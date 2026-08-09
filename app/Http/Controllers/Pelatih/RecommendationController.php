<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function store(Request $request, SchoolClass $class, Student $student)
    {
        abort_unless($class->coach_id === auth()->id(), 403);

        $currentLevel = $class->level;
        $minLevel = ($currentLevel ?? 0) + 1;

        $validated = $request->validate([
            'recommended_class_id' => ['nullable', 'exists:classes,id', 'required_without:recommended_level'],
            'recommended_level' => ['nullable', 'integer', 'min:'.$minLevel, 'max:3', 'required_without:recommended_class_id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $targetClass = null;
        if ($validated['recommended_class_id'] ?? null) {
            $targetClass = SchoolClass::find($validated['recommended_class_id']);
            abort_unless($targetClass && $targetClass->program_id === $class->program_id, 422, 'Kelas target harus satu program.');
            abort_unless($currentLevel === null || $targetClass->level > $currentLevel, 422, 'Kelas target harus level lebih tinggi.');
        }

        $alreadyPending = ClassRecommendation::where('student_id', $student->id)
            ->where('status', 'pending')
            ->exists();

        abort_if($alreadyPending, 422, 'Masih ada rekomendasi pending untuk siswa ini.');

        ClassRecommendation::create([
            'student_id' => $student->id,
            'from_user_id' => auth()->id(),
            'current_class_id' => $class->id,
            'recommended_class_id' => $targetClass?->id,
            'recommended_level' => $targetClass ? $targetClass->level : ($validated['recommended_level'] ?? null),
            'note' => $validated['note'] ?? null,
        ]);

        return back()->with('success', 'Rekomendasi naik kelas berhasil dibuat.');
    }
}
