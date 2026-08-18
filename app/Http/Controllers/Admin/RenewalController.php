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
        $enrollments = ClassStudent::with(['student.parent', 'schoolClass.program', 'attendances'])
            ->where('is_active', true)
            ->get()
            ->filter(fn ($enrollment) => $enrollment->needsRenewalConfirmation())
            ->sortBy('student.full_name');

        return view('admin.renewals.index', compact('enrollments'));
    }

    public function confirmRenewal(Student $student, ClassStudent $classStudent)
    {
        abort_unless($classStudent->student_id === $student->id, 404);
        abort_unless($classStudent->needsRenewalConfirmation(), 403);

        $defer = $classStudent->schoolClass?->program?->billing_type === 'per_paket'
            && $classStudent->remainingSessions() > 0;

        DB::transaction(function () use ($classStudent, $defer) {
            if ($defer) {
                $classStudent->update([
                    'renewal_status' => ClassStudent::RENEWAL_STATUS_LANJUT,
                ]);

                return;
            }

            $classStudent->renewIntoNextPeriod();
        });

        if ($defer) {
            return back()->with('success', 'Paket '.$student->full_name.' dikonfirmasi lanjut. Sisa sesi akan dihabiskan dulu, lalu periode paket baru dibuat otomatis.');
        }

        return back()->with('success', 'Paket '.$student->full_name.' diperpanjang. Periode paket baru berhasil dibuat.');
    }

    public function declineRenewal(Student $student, ClassStudent $classStudent)
    {
        abort_unless($classStudent->student_id === $student->id, 404);
        abort_unless($classStudent->needsRenewalConfirmation(), 403);

        $classStudent->update([
            'is_active' => false,
            'renewal_status' => ClassStudent::RENEWAL_STATUS_BERHENTI,
            'ended_at' => now(),
        ]);

        return back()->with('success', $student->full_name.' ditandai tidak melanjutkan paket.');
    }
}
