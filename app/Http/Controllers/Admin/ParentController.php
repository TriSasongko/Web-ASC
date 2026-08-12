<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scopes\ActiveParentScope;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array($request->integer('per_page'), [5, 10, 25, 50], true)
            ? $request->integer('per_page')
            : 10;

        $parents = User::where('role', 'orang_tua')
            ->with('students')
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        return view('admin.parents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'orang_tua';

        User::create($validated);

        return redirect()->route('admin.parents.index')->with('success', 'Orang tua berhasil ditambahkan.');
    }

    public function edit(User $parent)
    {
        abort_unless($parent->role === 'orang_tua', 404);

        return view('admin.parents.edit', compact('parent'));
    }

    public function show(User $parent)
    {
        abort_unless($parent->role === 'orang_tua', 404);

        $students = Student::withoutGlobalScope(ActiveParentScope::class)
            ->where('parent_id', $parent->id)
            ->with(['classes.program'])
            ->get();

        return view('admin.parents.show', compact('parent', 'students'));
    }

    public function toggleActive(User $parent)
    {
        abort_unless($parent->role === 'orang_tua', 404);
        $parent->update(['is_active' => ! $parent->is_active]);

        return back()->with('success', $parent->is_active ? 'Akun orang tua diaktifkan kembali.' : 'Akun orang tua dinonaktifkan.');
    }

    public function update(Request $request, User $parent)
    {
        abort_unless($parent->role === 'orang_tua', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($parent->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $parent->update($validated);

        return redirect()->route('admin.parents.index')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy(User $parent)
    {
        abort_unless($parent->role === 'orang_tua', 404);
        $parent->delete();

        return back()->with('success', 'Orang tua berhasil dihapus.');
    }

    public function resetPassword(User $parent)
    {
        abort_unless($parent->role === 'orang_tua', 404);
        $parent->update(['password' => Hash::make('password')]);

        return back()->with('success', 'Password orang tua direset ke default (password).');
    }
}
