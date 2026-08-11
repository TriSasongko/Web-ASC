<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;
use App\Models\ClassSchedule;
use App\Models\ClassStudent;
use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

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

    // Ajukan naik kelas oleh admin: membuat rekomendasi berstatus menunggu_ortu.
    // Siswa baru benar-benar dipindahkan setelah orang tua konfirmasi via WA.
    public function move(Request $request, ClassStudent $enrollment)
    {
        $validated = $request->validate([
            'target_class_id' => ['required', 'exists:classes,id'],
        ]);

        $target = SchoolClass::findOrFail($validated['target_class_id']);

        $currentLevel = $enrollment->schoolClass?->level;

        if ($currentLevel !== null && $currentLevel >= SchoolClass::LEVEL_ELITE) {
            return back()->with('error', 'Siswa level Elite tidak dapat dinaikkan kelas.');
        }

        if ($currentLevel !== null && $target->level <= $currentLevel) {
            return back()->with('error', 'Kelas target harus level lebih tinggi.');
        }

        if (! ($target->program_id === $enrollment->schoolClass->program_id || $target->program?->isKompetitif())) {
            return back()->with('error', 'Kelas target harus satu program atau berada di program Kompetitif.');
        }

        $program = $enrollment->schoolClass?->program;
        if ($program?->billing_type === 'per_paket' && ! $enrollment->isFinished()) {
            return back()->with('error', 'Paket '.$program->name.' belum habis, siswa belum dapat dinaikkan kelas.');
        }

        $activeRec = ClassRecommendation::where('student_id', $enrollment->student_id)
            ->whereIn('status', ['pending', 'menunggu_ortu'])
            ->exists();

        if ($activeRec) {
            return back()->with('error', 'Masih ada rekomendasi naik kelas aktif untuk siswa ini.');
        }

        ClassRecommendation::create([
            'student_id' => $enrollment->student_id,
            'from_user_id' => auth()->id(),
            'current_class_id' => $enrollment->class_id,
            'recommended_class_id' => $target->id,
            'recommended_level' => $target->level,
            'status' => 'menunggu_ortu',
            'approved_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.recommendations.index')
            ->with('success', 'Pengajuan naik kelas '.$enrollment->student->full_name.' ke '.$target->name.' dibuat. Silakan konfirmasi ke orang tua via WhatsApp.');
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
