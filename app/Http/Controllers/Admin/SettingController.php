<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingCoach;
use App\Models\LandingGalleryImage;
use App\Models\LandingProgram;
use App\Models\LandingSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    private const TABS = ['hero', 'tentang', 'program', 'galeri', 'kontak'];

    public function edit(Request $request)
    {
        $tab = $request->query('tab', 'hero');
        $tab = in_array($tab, self::TABS, true) ? $tab : 'hero';

        return view('admin.settings.edit', [
            'tab' => $tab,
            'settings' => LandingSetting::allValues(),
            'coaches' => LandingCoach::orderBy('sort_order')->orderBy('id')->get(),
            'programs' => LandingProgram::orderBy('sort_order')->orderBy('id')->get(),
            'gallery' => LandingGalleryImage::orderBy('sort_order')->orderBy('id')->get(),
            'adminPhone' => User::where('role', 'admin')->orderBy('id')->value('phone'),
            'adminAddress' => User::where('role', 'admin')->orderBy('id')->value('address'),
        ]);
    }

    public function updateHero(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_highlight' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string', 'max:1000'],
            'hero_image' => ['nullable', 'url'],
            'hero_side_image' => ['nullable', 'url'],
            'hero_side_image_alt' => ['nullable', 'string', 'max:255'],
            'hero_cta_primary' => ['nullable', 'string', 'max:100'],
            'hero_cta_secondary' => ['nullable', 'string', 'max:100'],
        ], [
            'hero_title.required' => 'Judul hero wajib diisi.',
            'hero_subtitle.required' => 'Deskripsi hero wajib diisi.',
            'hero_image.url' => 'URL gambar hero harus berupa tautan yang valid.',
            'hero_side_image.url' => 'URL foto samping hero harus berupa tautan yang valid.',
        ]);

        $this->saveSettings(array_intersect_key($validated, array_flip([
            'hero_title', 'hero_highlight', 'hero_subtitle', 'hero_image',
            'hero_side_image', 'hero_side_image_alt', 'hero_cta_primary', 'hero_cta_secondary',
        ])));

        return redirect()->route('admin.settings.edit', ['tab' => 'hero'])
            ->with('success', 'Konten hero landing page berhasil diperbarui.');
    }

    public function updateTentang(Request $request)
    {
        $validated = $request->validate([
            'tentang_heading' => ['required', 'string', 'max:255'],
            'tentang_text' => ['required', 'string', 'max:5000'],
            'tentang_visi' => ['required', 'string', 'max:2000'],
            'tentang_misi' => ['nullable', 'string', 'max:5000'],
            'tentang_years' => ['nullable', 'string', 'max:50'],
            'tentang_years_label' => ['nullable', 'string', 'max:100'],
            'tentang_image' => ['nullable', 'url'],
        ], [
            'tentang_heading.required' => 'Judul seksi Tentang wajib diisi.',
            'tentang_text.required' => 'Deskripsi Tentang wajib diisi.',
            'tentang_visi.required' => 'Visi wajib diisi.',
            'tentang_image.url' => 'URL gambar harus berupa tautan yang valid.',
        ]);

        $this->saveSettings(array_intersect_key($validated, array_flip([
            'tentang_heading', 'tentang_text', 'tentang_visi', 'tentang_misi',
            'tentang_years', 'tentang_years_label', 'tentang_image',
        ])));

        return redirect()->route('admin.settings.edit', ['tab' => 'tentang'])
            ->with('success', 'Konten seksi Tentang berhasil diperbarui.');
    }

    public function updateProgram(Request $request)
    {
        $validated = $request->validate([
            'program_heading' => ['required', 'string', 'max:255'],
            'program_subtitle' => ['nullable', 'string', 'max:1000'],
        ], [
            'program_heading.required' => 'Judul seksi Program wajib diisi.',
        ]);

        $this->saveSettings([
            'program_heading' => $validated['program_heading'],
            'program_subtitle' => $validated['program_subtitle'] ?? null,
        ]);

        return redirect()->route('admin.settings.edit', ['tab' => 'program'])
            ->with('success', 'Konten seksi Program berhasil diperbarui.');
    }

    public function updateGaleri(Request $request)
    {
        $validated = $request->validate([
            'galeri_heading' => ['required', 'string', 'max:255'],
            'galeri_subtitle' => ['nullable', 'string', 'max:1000'],
        ], [
            'galeri_heading.required' => 'Judul seksi Galeri wajib diisi.',
        ]);

        $this->saveSettings([
            'galeri_heading' => $validated['galeri_heading'],
            'galeri_subtitle' => $validated['galeri_subtitle'] ?? null,
        ]);

        return redirect()->route('admin.settings.edit', ['tab' => 'galeri'])
            ->with('success', 'Konten seksi Galeri berhasil diperbarui.');
    }

    public function updateKontak(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'kontak_email' => ['required', 'email', 'max:255'],
            'kontak_instagram' => ['nullable', 'url', 'max:255'],
            'kontak_instagram_handle' => ['nullable', 'string', 'max:100'],
            'kontak_maps_url' => ['nullable', 'url', 'max:2000'],
            'kontak_hours_weekday' => ['nullable', 'string', 'max:255'],
            'kontak_hours_weekend' => ['nullable', 'string', 'max:255'],
        ], [
            'phone.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'kontak_email.required' => 'Email wajib diisi.',
            'kontak_email.email' => 'Format email tidak valid.',
            'kontak_instagram.url' => 'URL Instagram harus berupa tautan yang valid.',
            'kontak_maps_url.url' => 'URL peta harus berupa tautan yang valid.',
        ]);

        $admin = User::where('role', 'admin')->orderBy('id')->first();

        if ($admin) {
            $admin->update([
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);
        }

        $this->saveSettings(array_intersect_key($validated, array_flip([
            'kontak_email', 'kontak_instagram', 'kontak_instagram_handle',
            'kontak_maps_url', 'kontak_hours_weekday', 'kontak_hours_weekend',
        ])));

        return redirect()->route('admin.settings.edit', ['tab' => 'kontak'])
            ->with('success', 'Pengaturan kontak berhasil diperbarui.');
    }

    public function storeCoach(Request $request)
    {
        $validated = $this->validateCoach($request);

        LandingCoach::create($validated);

        return redirect()->route('admin.settings.edit', ['tab' => 'tentang'])
            ->with('success', 'Coach berhasil ditambahkan.');
    }

    public function updateCoach(Request $request, LandingCoach $coach)
    {
        $validated = $this->validateCoach($request);

        $coach->update($validated);

        return redirect()->route('admin.settings.edit', ['tab' => 'tentang'])
            ->with('success', 'Data coach berhasil diperbarui.');
    }

    public function destroyCoach(LandingCoach $coach)
    {
        $coach->delete();

        return redirect()->route('admin.settings.edit', ['tab' => 'tentang'])
            ->with('success', 'Coach berhasil dihapus.');
    }

    public function storeProgram(Request $request)
    {
        $validated = $this->validateProgram($request);

        LandingProgram::create($validated);

        return redirect()->route('admin.settings.edit', ['tab' => 'program'])
            ->with('success', 'Program berhasil ditambahkan.');
    }

    public function updateProgramItem(Request $request, LandingProgram $program)
    {
        $validated = $this->validateProgram($request);

        $program->update($validated);

        return redirect()->route('admin.settings.edit', ['tab' => 'program'])
            ->with('success', 'Data program berhasil diperbarui.');
    }

    public function destroyProgram(LandingProgram $program)
    {
        $program->delete();

        return redirect()->route('admin.settings.edit', ['tab' => 'program'])
            ->with('success', 'Program berhasil dihapus.');
    }

    public function storeGallery(Request $request)
    {
        $validated = $this->validateGallery($request);

        LandingGalleryImage::create($validated);

        return redirect()->route('admin.settings.edit', ['tab' => 'galeri'])
            ->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function updateGallery(Request $request, LandingGalleryImage $gallery)
    {
        $validated = $this->validateGallery($request);

        $gallery->update($validated);

        return redirect()->route('admin.settings.edit', ['tab' => 'galeri'])
            ->with('success', 'Data foto galeri berhasil diperbarui.');
    }

    public function destroyGallery(LandingGalleryImage $gallery)
    {
        $gallery->delete();

        return redirect()->route('admin.settings.edit', ['tab' => 'galeri'])
            ->with('success', 'Foto galeri berhasil dihapus.');
    }

    private function validateCoach(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'photo_url' => ['nullable', 'url'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'name.required' => 'Nama coach wajib diisi.',
            'position.required' => 'Jabatan coach wajib diisi.',
            'description.required' => 'Deskripsi coach wajib diisi.',
            'photo_url.url' => 'URL foto coach harus berupa tautan yang valid.',
        ]);
    }

    private function validateProgram(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'integer', 'min:0'],
            'billing_unit' => ['required', Rule::in(['/sesi', '/bulan', '/paket'])],
            'features' => ['nullable', 'string', 'max:5000'],
            'badge' => ['nullable', 'string', 'max:100'],
            'button_label' => ['nullable', 'string', 'max:100'],
            'featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'name.required' => 'Nama program wajib diisi.',
            'billing_unit.in' => 'Satuan harga tidak valid.',
        ]);
    }

    private function validateGallery(Request $request): array
    {
        return $request->validate([
            'image_url' => ['required', 'url'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['nullable', Rule::in(['Latihan', 'Kejuaraan', 'Keceriaan', 'Video'])],
            'aspect' => ['required', Rule::in(['4/3', '3/4', 'square', 'video', '4/5'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'image_url.required' => 'URL foto galeri wajib diisi.',
            'image_url.url' => 'URL foto galeri harus berupa tautan yang valid.',
            'category.in' => 'Kategori foto tidak valid.',
            'aspect.in' => 'Rasio foto tidak valid.',
        ]);
    }

    private function saveSettings(array $values): void
    {
        foreach ($values as $key => $value) {
            LandingSetting::set($key, $value);
        }
    }
}
