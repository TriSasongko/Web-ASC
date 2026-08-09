<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class ERaportController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with([
            'classes' => fn ($q) => $q->wherePivot('is_active', true)->with('program'),
            'developments.schoolClass.program',
        ])
            ->where('parent_id', auth()->id())
            ->orderBy('full_name')
            ->get();

        return view('orangtua.eraports.index', compact('students'));
    }
}
