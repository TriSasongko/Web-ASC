<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {
        $recommendations = ClassRecommendation::with(['student', 'from', 'currentClass', 'recommendedClass'])
            ->latest()
            ->paginate(15);

        return view('admin.recommendations.index', compact('recommendations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'current_class_id' => ['nullable', 'exists:classes,id'],
            'recommended_class_id' => ['nullable', 'exists:classes,id', 'required_without:recommended_level'],
            'recommended_level' => ['nullable', 'integer', 'min:1', 'required_without:recommended_class_id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        ClassRecommendation::create([
            'student_id' => $validated['student_id'],
            'from_user_id' => auth()->id(),
            'current_class_id' => $validated['current_class_id'] ?? null,
            'recommended_class_id' => $validated['recommended_class_id'] ?? null,
            'recommended_level' => $validated['recommended_level'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        return back()->with('success', 'Rekomendasi naik kelas berhasil dibuat.');
    }

    public function destroy(ClassRecommendation $recommendation)
    {
        $recommendation->delete();

        return back()->with('success', 'Rekomendasi berhasil dihapus.');
    }
}
