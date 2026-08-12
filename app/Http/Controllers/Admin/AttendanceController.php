<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $recordedCount = Attendance::count();

        return view('admin.attendances.index', compact('recordedCount'));
    }

    public function create()
    {
        $classes = SchoolClass::with('program')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $students = Student::with(['classes' => function ($q) {
            $q->wherePivot('is_active', true)->with('program');
        }])
            ->whereHas('enrollments', fn ($q) => $q->where('is_active', true))
            ->orderBy('full_name')
            ->get();

        return view('admin.attendances.create', compact('classes', 'students') + [
            'attendanceByDate' => $this->attendanceByDate(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'attendance' => ['required', 'array'],
            'attendance.*' => ['integer', 'exists:students,id'],
        ]);

        $validated['session_number'] ??= 1;

        $studentIds = array_values(array_unique($validated['attendance']));

        $alreadyRecorded = Attendance::whereIn('student_id', $studentIds)
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->pluck('student_id')
            ->unique();

        if ($alreadyRecorded->isNotEmpty()) {
            $names = Student::whereIn('id', $alreadyRecorded)
                ->orderBy('full_name')
                ->pluck('full_name')
                ->implode(', ');

            return back()
                ->withInput()
                ->withErrors(['attendance' => "Siswa berikut sudah tercatat hadir pada tanggal tersebut: {$names}. Setiap siswa hanya dapat diabsensi sekali per hari."]);
        }

        DB::transaction(function () use ($validated, $studentIds) {
            foreach ($studentIds as $studentId) {
                $activeEnrollment = ClassStudent::with('schoolClass.program')
                    ->where('student_id', $studentId)
                    ->where('is_active', true)
                    ->first();

                // Jika periode aktif sudah selesai & status lanjut, buka periode paket baru
                // dulu agar absensi ini tercatat pada paket yang baru.
                if ($activeEnrollment?->schoolClass?->program?->billing_type === 'per_paket') {
                    $activeEnrollment->completeRenewalIfReady();

                    $activeEnrollment = ClassStudent::with('schoolClass.program')
                        ->where('student_id', $studentId)
                        ->where('is_active', true)
                        ->first();
                }

                Attendance::create([
                    'class_id' => $activeEnrollment?->class_id,
                    'class_student_id' => $activeEnrollment?->id,
                    'student_id' => $studentId,
                    'recorded_by' => auth()->id(),
                    'attendance_date' => $validated['attendance_date'],
                    'session_number' => $validated['session_number'],
                    'location' => $validated['location'] ?? null,
                ]);

                if ($activeEnrollment?->schoolClass?->program?->billing_type === 'per_paket') {
                    $program = $activeEnrollment->schoolClass->program;

                    if ($program->total_sessions && $activeEnrollment->sessions_completed >= $program->total_sessions) {
                        continue;
                    }

                    $activeEnrollment->increment('sessions_completed');
                    $activeEnrollment->completeRenewalIfReady();
                    $activeEnrollment->markForRenewalIfNeeded();
                }
            }
        });

        return redirect()->route('admin.attendances.history')
            ->with('success', 'Absensi berhasil disimpan oleh Admin.');
    }

    public function history(Request $request)
    {
        $attendances = Attendance::with(['student', 'recorder', 'schoolClass'])
            ->when($request->student_name, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('full_name', 'like', '%'.$request->student_name.'%')))
            ->when($request->date_from, fn ($q) => $q->whereDate('attendance_date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('attendance_date', '<=', $request->date_to))
            ->orderByDesc('attendance_date')
            ->paginate(20)
            ->withQueryString();

        return view('admin.attendances.history', compact('attendances'));
    }

    public function edit(Attendance $attendance)
    {
        return view('admin.attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $exists = Attendance::where('student_id', $attendance->student_id)
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->where('id', '!=', $attendance->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['attendance_date' => 'Tanggal ini sudah tercatat untuk siswa tersebut.']);
        }

        $attendance->update($validated);

        return redirect()->route('admin.attendances.history')
            ->with('success', 'Tanggal absensi berhasil dikoreksi.');
    }

    public function destroy(Attendance $attendance)
    {
        $enrollment = $attendance->class_student_id
            ? ClassStudent::find($attendance->class_student_id)
            : null;

        if ($enrollment && $enrollment->schoolClass?->program?->billing_type === 'per_paket') {
            $enrollment->update([
                'sessions_completed' => DB::raw('GREATEST(0, sessions_completed - 1)'),
            ]);
        } elseif (! $enrollment && $attendance->class_id && $attendance->schoolClass?->program?->billing_type === 'per_paket') {
            $attendance->schoolClass->students()->updateExistingPivot($attendance->student_id, [
                'sessions_completed' => DB::raw('GREATEST(0, sessions_completed - 1)'),
            ]);
        }

        $attendance->delete();

        return back()->with('success', 'Data absensi berhasil dihapus.');
    }

    private function attendanceByDate(): array
    {
        return Attendance::query()
            ->get(['attendance_date', 'student_id'])
            ->groupBy(fn (Attendance $attendance) => $attendance->attendance_date->format('Y-m-d'))
            ->map(fn ($group) => $group->pluck('student_id')->map(fn ($id) => (int) $id)->all())
            ->all();
    }
}
