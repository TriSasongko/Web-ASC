<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassStudent;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class RenewalController extends Controller
{
    public function index()
    {
        $enrollments = ClassStudent::with(['student.parent', 'schoolClass.program'])
            ->where('is_active', true)
            ->where('renewal_status', ClassStudent::RENEWAL_STATUS_PERLU_KONFIRMASI)
            ->get()
            ->sortBy('student.full_name');

        return view('admin.renewals.index', compact('enrollments'));
    }

    public function confirmRenewal(Student $student, ClassStudent $classStudent)
    {
        abort_unless($classStudent->student_id === $student->id, 404);
        abort_unless($classStudent->renewal_status === ClassStudent::RENEWAL_STATUS_PERLU_KONFIRMASI, 403);

        DB::transaction(function () use ($classStudent) {
            $classStudent->update([
                'is_active' => false,
                'renewal_status' => ClassStudent::RENEWAL_STATUS_SELESAI,
                'ended_at' => now(),
            ]);

            ClassStudent::create([
                'class_id' => $classStudent->class_id,
                'student_id' => $classStudent->student_id,
                'level' => $classStudent->level,
                'registration_id' => $classStudent->registration_id,
                'sessions_completed' => 0,
                'is_active' => true,
                'renewal_status' => ClassStudent::RENEWAL_STATUS_AKTIF,
                'started_at' => now(),
                'renewed_from_id' => $classStudent->id,
            ]);
        });

        return back()->with('success', 'Paket '.$student->full_name.' diperpanjang. Periode paket baru berhasil dibuat.');
    }

    public function declineRenewal(Student $student, ClassStudent $classStudent)
    {
        abort_unless($classStudent->student_id === $student->id, 404);
        abort_unless($classStudent->renewal_status === ClassStudent::RENEWAL_STATUS_PERLU_KONFIRMASI, 403);

        $classStudent->update([
            'is_active' => false,
            'renewal_status' => ClassStudent::RENEWAL_STATUS_BERHENTI,
            'ended_at' => now(),
        ]);

        return back()->with('success', $student->full_name.' ditandai tidak melanjutkan paket.');
    }
}
