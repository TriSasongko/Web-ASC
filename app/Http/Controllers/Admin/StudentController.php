<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->integer('per_page'), [5, 10, 25, 50], true)
            ? $request->integer('per_page')
            : 10;

        $status = in_array($request->input('status'), ['semua', 'aktif', 'perlu_konfirmasi', 'berhenti', 'pindah'], true)
            ? $request->input('status')
            : 'aktif';

        $level = in_array($request->integer('level'), array_keys(SchoolClass::levelOptions()), true)
            ? $request->integer('level')
            : null;

        $students = Student::with(['parent', 'classes.program'])
            ->whereHas('registrations', fn ($q) => $q->where('status', 'diterima'))
            ->when($status !== 'semua' || $level !== null, function ($q) use ($status, $level) {
                $q->whereHas('enrollments', function ($q) use ($status, $level) {
                    if ($status !== 'semua') {
                        if ($status === 'aktif') {
                            $q->where('is_active', true);
                        } else {
                            $q->where('renewal_status', $status);
                        }
                    }

                    if ($level !== null) {
                        $q->whereHas('schoolClass', fn ($cq) => $cq->where('level', $level));
                    }
                });
            })
            ->when($request->search, fn ($q) => $q->where('full_name', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'status', 'level'));
    }

    public function show(Student $student)
    {
        $student->load(['parent', 'classes.program']);

        $attendances = Attendance::with('recorder')
            ->where('student_id', $student->id)
            ->orderBy('attendance_date')
            ->get();

        $linked = $attendances->whereNotNull('class_student_id')->groupBy('class_student_id');

        // Absensi lama tanpa class_student_id: lampirkan ke periode yang rentang tanggalnya sesuai
        $legacy = $attendances->whereNull('class_student_id');

        foreach ($legacy->groupBy('class_id') as $classId => $records) {
            $periods = $student->classes->where('id', $classId);

            if ($periods->isEmpty()) {
                continue;
            }

            foreach ($records as $record) {
                $target = $periods
                    ->filter(fn ($c) => $this->periodContains($c->pivot, $record->attendance_date))
                    ->first()
                    ?? $periods->first(fn ($c) => $c->pivot->is_active)
                    ?? $periods->first();

                if ($target === null) {
                    continue;
                }

                $bucket = $linked->get($target->pivot->id) ?? collect();
                $linked->put($target->pivot->id, $bucket->push($record));
            }
        }

        $attendanceLists = [];

        foreach ($student->classes as $class) {
            $records = $linked->get($class->pivot->id) ?? collect();
            $total = $class->program->total_sessions;

            if ($total === null) {
                // Billing bulanan (Kompetitif): tampilkan absensi bulan berjalan
                $records = $records
                    ->filter(fn ($r) => $r->attendance_date->between(now()->startOfMonth(), now()->endOfMonth()))
                    ->sortBy('attendance_date')
                    ->values();
            } else {
                // Billing per paket: tampilkan sesuai total sesi paket (mis. 8 atau 4)
                $records = $records
                    ->sortByDesc('attendance_date')
                    ->take($total)
                    ->sortBy('attendance_date')
                    ->values();
            }

            $attendanceLists[$class->pivot->id] = $records;
        }

        return view('admin.students.show', compact('student', 'attendanceLists'));
    }

    public function edit(Student $student)
    {
        $student->load('parent');

        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['required', 'in:L,P'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'height' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'address' => ['nullable', 'string'],
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    private function periodContains($pivot, Carbon $date): bool
    {
        $started = $pivot->started_at ? Carbon::parse($pivot->started_at) : null;
        $ended = $pivot->ended_at ? Carbon::parse($pivot->ended_at) : null;

        if ($started !== null && $date->lt($started)) {
            return false;
        }

        return $ended === null || $date->lte($ended);
    }
}
