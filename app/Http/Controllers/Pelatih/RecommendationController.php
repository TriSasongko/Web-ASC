<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;
use App\Models\ClassStudent;
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

        $enrollment = ClassStudent::where('student_id', $student->id)
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->latest()
            ->first();

        $program = $enrollment?->schoolClass?->program;
        if ($program?->billing_type === 'per_paket' && ! $enrollment->isFinished()) {
            abort(422, 'Paket '.$program->name.' belum habis, siswa belum dapat direkomendasikan naik kelas.');
        }

        $activeRec = ClassRecommendation::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'menunggu_ortu'])
            ->exists();

        abort_if($activeRec, 422, 'Masih ada rekomendasi naik kelas aktif untuk siswa ini.');

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
