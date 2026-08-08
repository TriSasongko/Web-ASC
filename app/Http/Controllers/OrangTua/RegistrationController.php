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

        return view('orangtua.registrations.index', compact('registrations'));
    }

    public function create()
    {
        if (empty(auth()->user()->phone)) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Mohon lengkapi Nomor HP/WhatsApp Anda terlebih dahulu sebelum mendaftarkan anak.');
        }

        $programs = Program::where('is_active', true)->get();
        return view('orangtua.registrations.create', compact('programs'));
    }

    public function store(Request $request)
    {
        if (empty(auth()->user()->phone)) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Mohon lengkapi Nomor HP/WhatsApp Anda terlebih dahulu.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['required', 'in:L,P'],
            'weight' => ['nullable', 'numeric'],
            'height' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string'],
            'program_id' => ['required', 'exists:programs,id'],
        ]);

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
