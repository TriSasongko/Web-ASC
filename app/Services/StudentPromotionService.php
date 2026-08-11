<?php

namespace App\Services;

use App\Models\ClassStudent;
use App\Models\Registration;
use App\Models\SchoolClass;
use Illuminate\Validation\ValidationException;

class StudentPromotionService
{
    // Pindahkan siswa ke kelas target: nonaktifkan enrolment lama, buat registrasi
    // dan enrolment baru di kelas target. Mengembalikan enrolment baru.
    public function promote(int $studentId, ?ClassStudent $current, SchoolClass $target): ClassStudent
    {
        $currentLevel = $current?->schoolClass?->level;

        if ($currentLevel !== null && $currentLevel >= SchoolClass::LEVEL_ELITE) {
            throw ValidationException::withMessages(['target_class_id' => 'Siswa level Elite tidak dapat dinaikkan kelas.']);
        }

        if ($currentLevel !== null && $target->level <= $currentLevel) {
            throw ValidationException::withMessages(['target_class_id' => 'Kelas target harus level lebih tinggi.']);
        }

        if ($current !== null && ! ($target->program_id === $current->schoolClass->program_id || $target->program?->isKompetitif())) {
            throw ValidationException::withMessages(['target_class_id' => 'Kelas target harus satu program atau berada di program Kompetitif.']);
        }

        if ($target->capacity && $target->students()->count() >= $target->capacity) {
            throw ValidationException::withMessages(['target_class_id' => 'Kapasitas kelas target sudah penuh.']);
        }

        return \DB::transaction(function () use ($studentId, $current, $target) {
            if ($current) {
                $current->update(['is_active' => false, 'renewal_status' => 'pindah']);
            }

            $registration = Registration::create([
                'student_id' => $studentId,
                'program_id' => $target->program_id,
                'status' => 'diterima',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            return ClassStudent::create([
                'class_id' => $target->id,
                'student_id' => $studentId,
                'level' => $target->level,
                'registration_id' => $registration->id,
                'sessions_completed' => 0,
                'is_active' => true,
                'renewal_status' => 'belum_konfirmasi',
                'started_at' => now(),
            ]);
        });
    }
}
