<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Development;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class ERaportController extends Controller
{
    public function show(Student $student, $developmentId)
    {
        $this->authorizeAccess($student);

        $development = Development::with(['schoolClass.program', 'coach'])->findOrFail($developmentId);

        $attendanceCount = Attendance::where('class_id', $development->class_id)
            ->where('student_id', $student->id)
            ->count();

        return view('eraport.show', compact('student', 'development', 'attendanceCount'));
    }

    public function downloadPdf(Student $student, $developmentId)
    {
        $this->authorizeAccess($student);

        $development = Development::with(['schoolClass.program', 'coach'])->findOrFail($developmentId);

        $attendanceCount = Attendance::where('class_id', $development->class_id)
            ->where('student_id', $student->id)
            ->count();

        $pdf = Pdf::loadView('eraport.pdf', compact('student', 'development', 'attendanceCount'));

        return $pdf->download('E-Raport-'.str_replace(' ', '-', $student->full_name).'-'.$development->period.'.pdf');
    }

    // Orang tua hanya boleh akses e-raport anaknya sendiri; admin boleh akses semua
    private function authorizeAccess(Student $student): void
    {
        $user = auth()->user();

        if ($user->role === 'orang_tua' && $student->parent_id !== $user->id) {
            abort(403);
        }
    }
}
