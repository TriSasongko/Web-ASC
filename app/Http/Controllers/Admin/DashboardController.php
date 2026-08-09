<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $alerts = DB::table('class_student')
            ->join('students', 'students.id', '=', 'class_student.student_id')
            ->join('users as parents', 'parents.id', '=', 'students.parent_id')
            ->join('classes', 'classes.id', '=', 'class_student.class_id')
            ->join('programs', 'programs.id', '=', 'classes.program_id')
            ->where('programs.billing_type', 'per_paket')
            ->whereNotNull('programs.total_sessions')
            ->whereColumn('class_student.sessions_completed', '>=', DB::raw('programs.total_sessions - 1'))
            ->where('parents.is_active', true)
            ->where('class_student.renewal_status', '!=', 'berhenti')
            ->select('classes.id as class_id', 'classes.name as class_name', DB::raw('count(*) as total'))
            ->groupBy('classes.id', 'classes.name')
            ->orderByDesc('total')
            ->get();

        $needConfirmationCount = $alerts->sum('total');

        return view('admin.dashboard', compact('alerts', 'needConfirmationCount'));
    }
}
