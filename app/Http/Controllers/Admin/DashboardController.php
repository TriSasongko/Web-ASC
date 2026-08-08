<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Siswa yang paketnya hampir habis (sisa 1x pertemuan) atau sudah habis
        $packageAlerts = DB::table('class_student')
            ->join('students', 'students.id', '=', 'class_student.student_id')
            ->join('users as parents', 'parents.id', '=', 'students.parent_id')
            ->join('classes', 'classes.id', '=', 'class_student.class_id')
            ->join('programs', 'programs.id', '=', 'classes.program_id')
            ->where('programs.billing_type', 'per_paket')
            ->whereNotNull('programs.total_sessions')
            ->whereColumn('class_student.sessions_completed', '>=', DB::raw('programs.total_sessions - 1'))
            ->select(
                'students.full_name as student_name',
                'parents.name as parent_name',
                'parents.phone as parent_phone',
                'classes.name as class_name',
                'programs.name as program_name',
                'class_student.sessions_completed',
                'programs.total_sessions'
            )
            ->orderByDesc('class_student.sessions_completed')
            ->get();

        return view('admin.dashboard', compact('packageAlerts'));
    }
}
