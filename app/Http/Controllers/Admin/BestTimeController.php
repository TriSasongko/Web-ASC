<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BestTime;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BestTimeController extends Controller
{
    private function ensureKompetitif(SchoolClass $class): void
    {
        abort_if(! $class->isKompetitif(), 403, 'Best time hanya tersedia untuk kelas Kompetitif.');
    }

    // Daftar siswa yang sudah punya catatan best time di semua kelas
    public function index(Request $request)
    {
        $students = Student::query()
            ->whereHas('bestTimes')
            ->when($request->search, fn ($q) => $q->where('full_name', 'like', '%'.$request->search.'%'))
            ->with(['bestTimes' => fn ($q) => $q->with('schoolClass')->latest('recorded_at')])
            ->orderBy('full_name')
            ->paginate(15);

        return view('admin.best-times.index', compact('students'));
    }

    // Daftar siswa aktif di kelas Kompetitif, untuk diisi best time-nya
    public function classIndex(SchoolClass $class)
    {
        $this->ensureKompetitif($class);

        $students = $class->students()->wherePivot('is_active', true)->get();

        return view('admin.best-times.class-index', compact('class', 'students'));
    }

    public function create(SchoolClass $class, Student $student)
    {
        $this->ensureKompetitif($class);
        $this->ensureInClass($class, $student);

        return view('admin.best-times.create', compact('class', 'student'));
    }

    public function store(Request $request, SchoolClass $class, Student $student)
    {
        $this->ensureKompetitif($class);
        $this->ensureInClass($class, $student);

        $validated = $request->validate([
            'recorded_at' => ['required', 'date'],
        ]);

        $entries = [];
        foreach (BestTime::styles() as $style => $styleLabel) {
            foreach (BestTime::distancesByStyle()[$style] as $distance) {
                $raw = $request->input('times.'.$style.'.'.$distance);
                if ($raw === null || trim($raw) === '') {
                    continue;
                }

                $ms = BestTime::parseTime($raw);
                if ($ms === null) {
                    throw ValidationException::withMessages([
                        'times.'.$style.'.'.$distance => sprintf(
                            'Waktu %s (%s, %s) tidak valid. Gunakan format Menit:Detik:MiliDetik, contoh: 01:25:37.',
                            $raw,
                            $styleLabel,
                            BestTime::distanceLabel($distance)
                        ),
                    ]);
                }

                $entries[] = ['style' => $style, 'distance' => $distance, 'time_ms' => $ms];
            }
        }

        if ($entries === []) {
            throw ValidationException::withMessages([
                'times' => 'Isi minimal satu waktu terlebih dahulu.',
            ]);
        }

        foreach ($entries as $entry) {
            BestTime::create([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'recorded_by' => auth()->id(),
                'style' => $entry['style'],
                'distance' => $entry['distance'],
                'time_ms' => $entry['time_ms'],
                'recorded_at' => $validated['recorded_at'],
            ]);
        }

        return redirect()->route('admin.classes.best-times.history', [$class, $student])
            ->with('success', 'Catatan best time siswa berhasil disimpan.');
    }

    // Grid best time + riwayat lengkap untuk 1 siswa
    public function history(SchoolClass $class, Student $student)
    {
        $this->ensureKompetitif($class);

        $records = BestTime::with('recorder')
            ->where('class_id', $class->id)
            ->where('student_id', $student->id)
            ->orderByDesc('recorded_at')
            ->orderByDesc('id')
            ->get();

        $best = [];
        foreach ($records as $record) {
            $best[$record->style][$record->distance] = min(
                $best[$record->style][$record->distance] ?? PHP_INT_MAX,
                $record->time_ms
            );
        }

        $recordsByDate = $records->groupBy(fn ($record) => $record->recorded_at->toDateString());

        return view('admin.best-times.history', compact('class', 'student', 'records', 'best', 'recordsByDate'));
    }

    public function destroy(BestTime $bestTime)
    {
        $bestTime->delete();

        return back()->with('success', 'Catatan best time berhasil dihapus.');
    }

    public function destroyMany(Request $request, SchoolClass $class, Student $student)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $deleted = BestTime::where('class_id', $class->id)
            ->where('student_id', $student->id)
            ->whereIn('id', $validated['ids'])
            ->delete();

        return back()->with('success', $deleted.' catatan best time berhasil dihapus.');
    }

    private function ensureInClass(SchoolClass $class, Student $student): void
    {
        $enrolled = $class->students()->wherePivot('is_active', true)->whereKey($student->id)->exists();
        abort_if(! $enrolled, 403, 'Siswa tidak terdaftar aktif di kelas ini.');
    }
}
