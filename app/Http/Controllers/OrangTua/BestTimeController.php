<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\BestTime;
use App\Models\Student;

class BestTimeController extends Controller
{
    // Best time anak-anak milik orang tua yang login (read-only)
    public function index()
    {
        $students = Student::where('parent_id', auth()->id())
            ->with('bestTimes')
            ->orderBy('full_name')
            ->get();

        $best = [];
        foreach ($students as $student) {
            foreach ($student->bestTimes as $record) {
                $best[$student->id][$record->style][$record->distance] = min(
                    $best[$student->id][$record->style][$record->distance] ?? PHP_INT_MAX,
                    $record->time_ms
                );
            }
        }

        $distances = BestTime::allDistances();

        return view('orangtua.best-times.index', compact('students', 'best', 'distances'));
    }
}
