<?php

namespace App\Http\Controllers\Pelatih;

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
        $recordedCount = Attendance::where('recorded_by', auth()->id())->count();

        return view('pelatih.attendances.index', compact('recordedCount'));
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

        return view('pelatih.attendances.create', compact('classes', 'students') + [
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

        return redirect()->route('pelatih.attendances.history')
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function history(Request $request)
    {
        $perPage = in_array($request->integer('per_page'), [5, 10, 25, 50], true)
            ? $request->integer('per_page')
            : 10;

        $attendances = Attendance::with(['student', 'recorder', 'schoolClass'])
            ->where('recorded_by', auth()->id())
            ->when($request->student_name, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('full_name', 'like', '%'.$request->student_name.'%')))
            ->when($request->date_from, fn ($q) => $q->whereDate('attendance_date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('attendance_date', '<=', $request->date_to))
            ->orderByDesc('attendance_date')
            ->paginate($perPage)
            ->withQueryString();

        return view('pelatih.attendances.history', compact('attendances'));
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
