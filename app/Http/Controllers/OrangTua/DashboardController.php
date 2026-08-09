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

        $pendingRecommendations = ClassRecommendation::with(['student', 'recommendedClass', 'currentClass', 'from'])
            ->whereHas('student', fn ($q) => $q->where('parent_id', auth()->id()))
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('orangtua.dashboard', compact('students', 'pendingRecommendations'));
    }
}
