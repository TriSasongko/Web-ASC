<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['parent', 'classes.program'])
            ->activeProgram()
            ->whereHas('registrations', fn ($q) => $q->where('status', 'diterima'))
            ->when($request->search, fn ($q) => $q->where('full_name', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load(['parent', 'classes.program', 'classes.coach', 'classes.schedules']);

        $attendances = Attendance::where('student_id', $student->id)
            ->orderBy('attendance_date')
            ->get()
            ->groupBy('class_id');

        $classGrids = [];
        $monthlyGrids = [];

        foreach ($student->classes as $class) {
            $pivot = $class->pivot;
            $renewedAt = $pivot->renewed_at ? Carbon::parse($pivot->renewed_at) : null;

            $records = ($attendances[$class->id] ?? collect())
                ->filter(fn ($r) => $renewedAt ? $r->attendance_date->gte($renewedAt) : true)
                ->sortBy('attendance_date')
                ->values();

            $total = $class->program->total_sessions;
            $isPaket = $class->program->billing_type === 'per_paket' && $total;

            if ($isPaket) {
                $classGrids[$class->id] = $this->paketGrid($total, $records);
            } else {
                $monthlyGrids[$class->id] = $this->monthlyGrids($class, $records);
            }
        }

        return view('admin.students.show', compact('student', 'classGrids', 'monthlyGrids'));
    }

    private function paketGrid(int $total, Collection $records): array
    {
        $cells = [];

        for ($i = 1; $i <= $total; $i++) {
            $rec = $records->get($i - 1);
            $cells[] = [
                'number' => $i,
                'attended' => $rec !== null,
                'date' => $rec?->attendance_date,
            ];
        }

        return $cells;
    }

    private function monthlyGrids(SchoolClass $class, Collection $records): array
    {
        $dayNumbers = ['senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4, 'jumat' => 5, 'sabtu' => 6, 'minggu' => 7];

        $months = $records->map(fn ($r) => $r->attendance_date->format('Y-m'))->unique()->values();
        if ($months->isEmpty()) {
            $months = collect([now()->format('Y-m')]);
        }

        $grids = [];
        foreach ($months as $ym) {
            $start = Carbon::createFromFormat('Y-m', $ym)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            $cells = [];

            if ($class->schedules->isEmpty()) {
                foreach ($records->filter(fn ($r) => $r->attendance_date->format('Y-m') === $ym) as $r) {
                    $cells[] = ['number' => null, 'attended' => true, 'date' => $r->attendance_date];
                }
            } else {
                for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    if (! $class->schedules->contains(fn ($s) => ($dayNumbers[$s->day] ?? 0) === $d->dayOfWeek)) {
                        continue;
                    }
                    $rec = $records->first(fn ($r) => $r->attendance_date->isSameDay($d));
                    $cells[] = ['number' => null, 'attended' => $rec !== null, 'date' => $d->copy()];
                }
            }

            $grids[] = ['month' => $start, 'cells' => $cells];
        }

        return $grids;
    }
}
