<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;
use App\Models\ClassStudent;
use App\Models\Registration;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RecommendationController extends Controller
{
    public function index()
    {
        $recommendations = ClassRecommendation::with(['student.parent', 'student.enrollments', 'from', 'currentClass', 'recommendedClass', 'approver'])
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

        $this->abortIfElite($validated['student_id'], $validated['current_class_id'] ?? null);

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

    // Langkah 1: admin menyetujui rekomendasi, lalu wajib konfirmasi ke orang tua.
    public function approve(ClassRecommendation $recommendation)
    {
        abort_unless($recommendation->status === 'pending', 422, 'Rekomendasi sudah diproses.');

        if (! $this->resolveTarget($recommendation)) {
            throw ValidationException::withMessages(['recommended_class_id' => 'Kelas target tidak ditemukan.']);
        }

        $recommendation->update([
            'status' => 'menunggu_ortu',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Rekomendasi disetujui. Silakan konfirmasi terlebih dahulu ke orang tua via WhatsApp.');
    }

    // Langkah 2: orang tua sudah konfirmasi (via WA), siswa dipindahkan ke kelas target.
    public function confirm(ClassRecommendation $recommendation)
    {
        abort_unless($recommendation->status === 'menunggu_ortu', 422, 'Rekomendasi belum disetujui atau sudah diproses.');

        $target = $this->resolveTarget($recommendation);

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

        // Naik level = pindah program, siswa diarahkan ke program kelas target (registrasi baru).
        $newRegistration = Registration::create([
            'student_id' => $recommendation->student_id,
            'program_id' => $target->program_id,
            'status' => 'diterima',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        ClassStudent::create([
            'class_id' => $target->id,
            'student_id' => $recommendation->student_id,
            'level' => $target->level,
            'registration_id' => $newRegistration->id,
            'sessions_completed' => 0,
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
        ]);

        $recommendation->update([
            'status' => 'diterima',
            'approved_by' => auth()->id(),
            'moved_at' => now(),
        ]);

        return back()->with('success', 'Orang tua sudah konfirmasi, siswa dipindahkan ke kelas '.$target->name.'.');
    }

    public function reject(ClassRecommendation $recommendation)
    {
        abort_unless(in_array($recommendation->status, ['pending', 'menunggu_ortu']), 422, 'Rekomendasi sudah diproses.');

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

    private function abortIfElite(int $studentId, ?int $currentClassId): void
    {
        $enrollment = ClassStudent::with('schoolClass')->where('student_id', $studentId)
            ->where('is_active', true)
            ->when($currentClassId, fn ($q) => $q->where('class_id', $currentClassId))
            ->latest()
            ->first();

        if (($enrollment?->schoolClass?->level ?? null) >= SchoolClass::LEVEL_ELITE) {
            throw ValidationException::withMessages([
                'recommended_level' => 'Siswa level Elite tidak dapat direkomendasikan naik kelas.',
            ]);
        }
    }

    private function resolveTarget(ClassRecommendation $recommendation): ?SchoolClass
    {
        if ($recommendation->recommendedClass) {
            return $recommendation->recommendedClass;
        }

        if (! $recommendation->recommended_level) {
            return null;
        }

        if ($recommendation->recommended_level > SchoolClass::LEVEL_BEGINNER) {
            return SchoolClass::where('is_active', true)
                ->where('level', $recommendation->recommended_level)
                ->whereHas('program', fn ($p) => $p->where('is_kompetitif', true))
                ->first();
        }

        return SchoolClass::where('is_active', true)
            ->where('level', SchoolClass::LEVEL_BEGINNER)
            ->first();
    }
}
