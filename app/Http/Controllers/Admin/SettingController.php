<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
        ], [
            'phone.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
        ]);

        $admin = User::where('role', 'admin')->orderBy('id')->first();

        if ($admin) {
            $admin->update([
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Pengaturan kontak admin berhasil diperbarui.');
    }
}
