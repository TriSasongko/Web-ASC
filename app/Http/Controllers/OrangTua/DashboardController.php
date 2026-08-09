<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\ClassRecommendation;

class DashboardController extends Controller
{
    public function index()
    {
        $students = auth()->user()->students()
            ->with(['classes.program', 'classes.coach'])
            ->get();

        $recommendations = ClassRecommendation::with(['student', 'recommendedClass', 'currentClass', 'from', 'approver'])
            ->whereHas('student', fn ($q) => $q->where('parent_id', auth()->id()))
            ->latest()
            ->get();

        return view('orangtua.dashboard', compact('students', 'recommendations'));
    }
}
