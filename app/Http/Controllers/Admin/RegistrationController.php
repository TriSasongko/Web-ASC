<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $registrations = Registration::with(['student.parent', 'program'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return view('admin.registrations.index', compact('registrations'));
    }

    public function show(Registration $registration)
    {
        $registration->load(['student.parent', 'program']);
        return view('admin.registrations.show', compact('registration'));
    }

    public function accept(Registration $registration)
    {
        $registration->update([
            'status' => 'diterima',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pendaftaran diterima.');
    }

    public function reject(Request $request, Registration $registration)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $registration->update([
            'status' => 'ditolak',
            'rejection_reason' => $request->rejection_reason,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pendaftaran ditolak.');
    }
}
