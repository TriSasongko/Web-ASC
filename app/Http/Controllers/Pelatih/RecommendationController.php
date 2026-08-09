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

        $validated = $request->validate([
            'recommended_class_id' => ['nullable', 'exists:classes,id', 'required_without:recommended_level'],
            'recommended_level' => ['nullable', 'integer', 'min:1', 'required_without:recommended_class_id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        ClassRecommendation::create([
            'student_id' => $student->id,
            'from_user_id' => auth()->id(),
            'current_class_id' => $class->id,
            'recommended_class_id' => $validated['recommended_class_id'] ?? null,
            'recommended_level' => $validated['recommended_level'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        return back()->with('success', 'Rekomendasi naik kelas berhasil dibuat.');
    }
}
