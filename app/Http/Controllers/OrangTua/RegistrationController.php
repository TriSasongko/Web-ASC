<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::whereHas('student', function ($q) {
            $q->where('parent_id', auth()->id());
        })
            ->with(['student', 'program'])
            ->latest()
            ->paginate(10);

        $pendingRegistration = Registration::whereHas('student', function ($q) {
            $q->where('parent_id', auth()->id());
        })
            ->with(['student', 'program'])
            ->where('status', 'menunggu_verifikasi')
            ->latest()
            ->first();

        return view('orangtua.registrations.index', compact('registrations', 'pendingRegistration'));
    }

    public function create()
    {
        $programs = Program::where('is_active', true)->get();

        return view('orangtua.registrations.create', compact('programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'full_name' => ['required', 'string', 'max:255'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:L,P'],
            'weight' => ['required', 'numeric'],
            'height' => ['required', 'numeric'],
            'address' => ['required', 'string'],
            'program_id' => ['required', 'exists:programs,id'],
        ]);

        auth()->user()->update(['phone' => $validated['phone']]);

        $student = Student::create([
            'parent_id' => auth()->id(),
            'full_name' => $validated['full_name'],
            'birth_place' => $validated['birth_place'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'],
            'weight' => $validated['weight'] ?? null,
            'height' => $validated['height'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        Registration::create([
            'student_id' => $student->id,
            'program_id' => $validated['program_id'],
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('orangtua.registrations.index')
            ->with('success', 'Pendaftaran berhasil dikirim. Silakan tunggu verifikasi dari Admin.');
    }
}
