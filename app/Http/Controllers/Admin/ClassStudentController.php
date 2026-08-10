<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassStudent;
use App\Models\Registration;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ClassStudentController extends Controller
{
    // Daftar siswa yang sudah diterima tapi belum ditempatkan di kelas manapun
    public function unplaced()
    {
        $registrations = Registration::with(['student', 'program'])
            ->where('status', 'diterima')
            ->whereDoesntHave('student.classes', function ($q) {
                $q->where('program_id', '=', 'registrations.program_id'); // fallback, refined below
            })
            ->get()
            ->filter(function ($reg) {
                // Siswa dianggap "belum ditempatkan" untuk program ini jika belum ada baris class_student
                // yang terhubung ke registration_id ini
                return ! \DB::table('class_student')
                    ->where('registration_id', $reg->id)
                    ->exists();
            });

        return view('admin.class-students.unplaced', compact('registrations'));
    }

    public function place(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
        ]);

        $class = SchoolClass::findOrFail($validated['class_id']);

        // Cek kapasitas kelas
        if ($class->capacity && $class->students()->count() >= $class->capacity) {
            return back()->with('error', 'Kapasitas kelas sudah penuh.');
        }

        $class->students()->attach($registration->student_id, [
            'registration_id' => $registration->id,
            'level' => $class->level,
            'sessions_completed' => 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Siswa berhasil ditempatkan ke kelas.');
    }

    public function remove(SchoolClass $class, $studentId)
    {
        $class->students()->detach($studentId);

        return back()->with('success', 'Siswa dikeluarkan dari kelas.');
    }

    public function renew(Request $request, ClassStudent $enrollment)
    {
        abort_unless($enrollment->schoolClass->program->billing_type === 'per_paket', 403);

        $enrollment->update([
            'sessions_completed' => 0,
            'is_active' => true,
            'renewal_status' => 'lanjut',
            'renewal_note' => $request->renewal_note ?: null,
            'renewed_at' => now(),
        ]);

        return back()->with('success', 'Paket '.$enrollment->student->full_name.' berhasil diperpanjang.');
    }

    public function stop(Request $request, ClassStudent $enrollment)
    {
        $enrollment->update([
            'is_active' => false,
            'renewal_status' => 'berhenti',
            'renewal_note' => $request->renewal_note ?: null,
        ]);

        return back()->with('success', 'Enrolment '.$enrollment->student->full_name.' ditandai berhenti.');
    }

    public function activate(ClassStudent $enrollment)
    {
        $enrollment->update([
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
        ]);

        return back()->with('success', 'Enrolment '.$enrollment->student->full_name.' diaktifkan kembali.');
    }
}
