<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $coaches = User::where('role', 'pelatih')
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(10);

        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('admin.coaches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('coaches', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'pelatih';

        User::create($validated);

        return redirect()->route('admin.coaches.index')->with('success', 'Pelatih berhasil ditambahkan.');
    }

    public function edit(User $coach)
    {
        abort_unless($coach->role === 'pelatih', 404);

        return view('admin.coaches.edit', compact('coach'));
    }

    public function update(Request $request, User $coach)
    {
        abort_unless($coach->role === 'pelatih', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($coach->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            if ($coach->photo) {
                Storage::disk('public')->delete($coach->photo);
            }
            $validated['photo'] = $request->file('photo')->store('coaches', 'public');
        }

        $coach->update($validated);

        return redirect()->route('admin.coaches.index')->with('success', 'Data pelatih berhasil diperbarui.');
    }

    public function destroy(User $coach)
    {
        abort_unless($coach->role === 'pelatih', 404);

        if ($coach->photo) {
            Storage::disk('public')->delete($coach->photo);
        }
        $coach->delete();

        return back()->with('success', 'Pelatih berhasil dihapus.');
    }

    public function toggleActive(User $coach)
    {
        abort_unless($coach->role === 'pelatih', 404);
        $coach->update(['is_active' => ! $coach->is_active]);

        return back()->with('success', 'Status pelatih diperbarui.');
    }

    public function toggleDevelopmentAccess(User $coach)
    {
        abort_unless($coach->role === 'pelatih', 404);
        $coach->update(['can_assess_developments' => ! $coach->can_assess_developments]);

        $status = $coach->can_assess_developments ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', 'Izin mengisi penilaian '.$status.' untuk '.$coach->name.'.');
    }

    public function resetPassword(User $coach)
    {
        abort_unless($coach->role === 'pelatih', 404);
        $coach->update(['password' => Hash::make('password')]);

        return back()->with('success', 'Password pelatih direset ke default (password).');
    }
}
