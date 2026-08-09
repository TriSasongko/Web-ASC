<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRecommendation;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
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
            ->join('users as coaches', 'coaches.id', '=', 'classes.coach_id')
            ->where('programs.billing_type', 'per_paket')
            ->whereNotNull('programs.total_sessions')
            ->whereColumn('class_student.sessions_completed', '>=', DB::raw('programs.total_sessions - 1'))
            ->where('parents.is_active', true)
            ->where('class_student.renewal_status', '!=', 'berhenti')
            ->select('classes.id as class_id', 'classes.name as class_name', 'coaches.name as coach_name', DB::raw('count(*) as total'))
            ->groupBy('classes.id', 'classes.name')
            ->orderByDesc('total')
            ->get();

        $totalStudents = Student::activeProgram()->count();

        $totalCoaches = User::where('role', 'pelatih')->where('is_active', true)->count();

        $pendingRegistrations = Registration::where('status', 'menunggu_verifikasi')->count();

        $needConfirmationCount = $alerts->sum('total');

        $growth = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $growth->push([
                'label' => $month->translatedFormat('M'),
                'count' => Student::where('created_at', '<=', $month->copy()->endOfMonth())->count(),
            ]);
        }

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $registrationStatus = Registration::whereBetween('created_at', [$monthStart, $monthEnd])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $statusData = [
            'menunggu' => $registrationStatus->get('menunggu_verifikasi', 0),
            'diterima' => $registrationStatus->get('diterima', 0),
            'ditolak' => $registrationStatus->get('ditolak', 0),
        ];

        $packageBuckets = DB::table('class_student')
            ->join('classes', 'classes.id', '=', 'class_student.class_id')
            ->join('programs', 'programs.id', '=', 'classes.program_id')
            ->where('programs.billing_type', 'per_paket')
            ->whereNotNull('programs.total_sessions')
            ->where('class_student.is_active', true)
            ->where('class_student.renewal_status', '!=', 'berhenti')
            ->selectRaw("CASE
                WHEN class_student.sessions_completed >= programs.total_sessions THEN 'habis'
                WHEN class_student.sessions_completed >= programs.total_sessions - 1 THEN 'hampir_habis'
                ELSE 'aktif'
            END as bucket, count(*) as total")
            ->groupBy('bucket')
            ->pluck('total', 'bucket');
        $packageData = [
            'aktif' => $packageBuckets->get('aktif', 0),
            'hampir_habis' => $packageBuckets->get('hampir_habis', 0),
            'habis' => $packageBuckets->get('habis', 0),
        ];

        $activities = collect();

        Registration::with(['student', 'program'])->latest()->limit(5)->get()->each(function ($r) use (&$activities) {
            $activities->push([
                'time' => $r->created_at,
                'icon' => 'person_add',
                'iconBg' => 'bg-secondary-container/20',
                'iconColor' => 'text-secondary',
                'subject' => $r->student?->full_name,
                'text' => 'mendaftar di Program '.($r->program?->name ?? '-'),
            ]);
        });

        Attendance::with(['student', 'schoolClass', 'recorder'])->latest()->limit(5)->get()->each(function ($a) use (&$activities) {
            $activities->push([
                'time' => $a->created_at,
                'icon' => 'fact_check',
                'iconBg' => 'bg-tertiary-fixed',
                'iconColor' => 'text-tertiary',
                'subject' => $a->recorder?->name,
                'text' => 'mengisi absensi Kelas '.($a->schoolClass?->name ?? '-'),
            ]);
        });

        ClassRecommendation::with(['student'])->latest()->limit(5)->get()->each(function ($c) use (&$activities) {
            $activities->push([
                'time' => $c->created_at,
                'icon' => 'verified',
                'iconBg' => 'bg-[#E8F5E9]',
                'iconColor' => 'text-[#2E7D32]',
                'subject' => $c->student?->full_name,
                'text' => 'rekomendasi naik kelas diajukan',
            ]);
        });

        $activities = $activities->sortByDesc('time')->take(6)->values();

        return view('admin.dashboard', compact(
            'alerts',
            'totalStudents',
            'totalCoaches',
            'pendingRegistrations',
            'needConfirmationCount',
            'growth',
            'statusData',
            'packageData',
            'activities'
        ));
    }
}
