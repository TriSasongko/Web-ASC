<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class ERaportController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->integer('per_page'), [5, 10, 25, 50], true)
            ? $request->integer('per_page')
            : 10;

        $students = Student::with([
            'classes' => fn ($q) => $q->wherePivot('is_active', true)->with('program'),
            'developments.schoolClass.program',
        ])
            ->activeProgram()
            ->when($request->search, fn ($q) => $q->where('full_name', 'like', '%'.$request->search.'%'))
            ->orderBy('full_name')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.eraports.index', compact('students'));
    }
}
