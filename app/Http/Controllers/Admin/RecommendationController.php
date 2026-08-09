<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;
use App\Models\ClassStudent;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
            'recommended_level' => ['nullable', 'integer', 'min:1', 'max:3', 'required_without:recommended_class_id'],
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

    public function approve(ClassRecommendation $recommendation)
    {
        abort_unless($recommendation->status === 'pending', 422, 'Rekomendasi sudah diproses.');

        $target = $recommendation->recommendedClass;

        if (! $target && $recommendation->recommended_level) {
            $target = SchoolClass::where('is_active', true)
                ->where('level', $recommendation->recommended_level)
                ->when($recommendation->current_class_id, fn ($q) => $q->where('program_id', $recommendation->currentClass?->program_id))
                ->first();
        }

        if (! $target) {
            throw ValidationException::withMessages(['recommended_class_id' => 'Kelas target tidak ditemukan.']);
        }

        if ($target->capacity && $target->students()->count() >= $target->capacity) {
            return back()->with('error', 'Kapasitas kelas target sudah penuh.');
        }

        $current = $recommendation->current_class_id
            ? ClassStudent::where('class_id', $recommendation->current_class_id)
                ->where('student_id', $recommendation->student_id)
                ->first()
            : null;

        if ($current) {
            $current->update(['is_active' => false, 'renewal_status' => 'pindah']);
        }

        ClassStudent::create([
            'class_id' => $target->id,
            'student_id' => $recommendation->student_id,
            'registration_id' => $current?->registration_id,
            'sessions_completed' => 0,
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
        ]);

        $recommendation->update([
            'status' => 'diterima',
            'approved_by' => auth()->id(),
            'moved_at' => now(),
        ]);

        return back()->with('success', 'Rekomendasi disetujui, siswa dipindahkan ke kelas '.$target->name.'.');
    }

    public function reject(ClassRecommendation $recommendation)
    {
        abort_unless($recommendation->status === 'pending', 422, 'Rekomendasi sudah diproses.');

        $recommendation->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Rekomendasi ditolak.');
    }

    public function destroy(ClassRecommendation $recommendation)
    {
        $recommendation->delete();

        return back()->with('success', 'Rekomendasi berhasil dihapus.');
    }
}
