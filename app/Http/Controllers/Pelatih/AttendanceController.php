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

        $blockedStudentIds = $students->filter(fn (Student $student) => $student->classes->isNotEmpty()
            && $student->classes->every(fn ($class) => $this->attendanceBlocked($class)))
            ->pluck('id')
            ->all();

        return view('pelatih.attendances.create', compact('classes', 'students', 'blockedStudentIds') + [
            'attendanceByDate' => $this->attendanceByDate(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'session_number' => ['nullable', 'integer', 'in:1,2'],
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

        $finishedNames = collect($studentIds)
            ->filter(function ($studentId) {
                $enrollments = ClassStudent::with('schoolClass.program')
                    ->where('student_id', $studentId)
                    ->where('is_active', true)
                    ->get();

                return $enrollments->isNotEmpty()
                    && $enrollments->every(fn ($enrollment) => $this->attendanceBlocked($enrollment));
            })
            ->map(fn ($id) => Student::find($id)?->full_name)
            ->filter()
            ->implode(', ');

        if ($finishedNames !== '') {
            return back()
                ->withInput()
                ->withErrors(['attendance' => "Siswa berikut paketnya sudah habis: {$finishedNames}. Lanjutkan atau hentikan paketnya dulu sebelum absensi berikutnya."]);
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

        return back()->with('success', 'Absensi berhasil disimpan.');
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

    /**
     * Absensi diblokir jika semua kelas aktif siswa adalah paket per_paket yang
     * sudah habis dan belum status lanjut. Status lanjut tetap boleh absen karena
     * store() otomatis membuka periode paket berikutnya.
     *
     * Menerima ClassStudent (dari store) atau SchoolClass + pivot (dari create).
     */
    private function attendanceBlocked(ClassStudent|SchoolClass $enrollmentOrClass): bool
    {
        $program = $enrollmentOrClass->schoolClass?->program ?? $enrollmentOrClass->program;

        if ($program?->billing_type !== 'per_paket' || $program->total_sessions === null) {
            return false;
        }

        $sessionsCompleted = $enrollmentOrClass->pivot->sessions_completed ?? $enrollmentOrClass->sessions_completed;
        $renewalStatus = $enrollmentOrClass->pivot->renewal_status ?? $enrollmentOrClass->renewal_status;

        if ($renewalStatus === ClassStudent::RENEWAL_STATUS_LANJUT) {
            return false;
        }

        return $sessionsCompleted >= $program->total_sessions;
    }
}
