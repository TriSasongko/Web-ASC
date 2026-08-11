<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\ClassStudent;
use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentPromotionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassStudentController extends Controller
{
    // Daftar siswa yang sudah diterima tapi belum ditempatkan di kelas manapun
    public function unplaced()
    {
        $registrations = Registration::with(['student', 'program.classes.schedules'])
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
            'schedule_ids' => ['nullable', 'array'],
            'schedule_ids.*' => ['integer', 'exists:class_schedules,id'],
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
            'started_at' => now(),
        ]);

        // Penugasan ke sesi latihan (hanya sesi milik kelas terpilih, siswa boleh di beberapa sesi)
        $scheduleIds = collect($validated['schedule_ids'] ?? [])
            ->filter(fn ($id) => ClassSchedule::find($id)?->class_id === $class->id)
            ->values()
            ->all();

        if ($scheduleIds !== []) {
            $registration->student->schedules()->syncWithoutDetaching($scheduleIds);
        }

        return back()->with('success', 'Siswa berhasil ditempatkan ke kelas.');
    }

    public function remove(SchoolClass $class, $studentId)
    {
        $class->students()->detach($studentId);

        $student = Student::find($studentId);
        if ($student) {
            $student->schedules()->detach($class->schedules()->pluck('class_schedules.id'));
        }

        return back()->with('success', 'Siswa dikeluarkan dari kelas.');
    }

    // Naikkan kelas siswa secara langsung oleh admin, tanpa melalui rekomendasi pelatih.
    public function move(Request $request, ClassStudent $enrollment)
    {
        $validated = $request->validate([
            'target_class_id' => ['required', 'exists:classes,id'],
        ]);

        $target = SchoolClass::findOrFail($validated['target_class_id']);

        try {
            app(StudentPromotionService::class)->promote($enrollment->student_id, $enrollment, $target);
        } catch (ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first());
        }

        return back()->with('success', $enrollment->student->full_name.' berhasil dinaikkan ke kelas '.$target->name.'.');
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
