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
        $currentLevel = $class->level;

        abort_if($currentLevel >= SchoolClass::LEVEL_ELITE, 422, 'Siswa level Elite tidak dapat direkomendasikan naik kelas.');

        $minLevel = ($currentLevel ?? 0) + 1;

        $validated = $request->validate([
            'recommended_class_id' => ['nullable', 'exists:classes,id', 'required_without:recommended_level'],
            'recommended_level' => ['nullable', 'integer', 'min:'.$minLevel, 'max:3', 'required_without:recommended_class_id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $targetClass = null;
        if ($validated['recommended_class_id'] ?? null) {
            $targetClass = SchoolClass::find($validated['recommended_class_id']);
            abort_unless($targetClass, 422, 'Kelas target tidak ditemukan.');
            abort_unless(
                $targetClass->program_id === $class->program_id || $targetClass->program?->isKompetitif(),
                422,
                'Kelas target harus satu program atau berada di program Kompetitif.'
            );
            abort_unless($targetClass->level > $currentLevel, 422, 'Kelas target harus level lebih tinggi.');
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
