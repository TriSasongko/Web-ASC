<?php

namespace App\Http\Controllers;

use App\Models\LandingCoach;
use App\Models\LandingGalleryImage;
use App\Models\LandingProgram;
use App\Models\LandingSetting;

class LandingController extends Controller
{
    // Jadwal default seksi Jadwal Latihan Reguler (dipakai bila belum diatur admin)
    private function defaultJadwal(): array
    {
        return [
            ['day' => 'Senin & Rabu', 'time' => '15:30 - 17:00', 'program' => 'Reguler Pemula & Lanjutan', 'location' => 'Kolam Renang Universitas Lampung'],
            ['day' => 'Selasa & Kamis', 'time' => '16:00 - 18:00', 'program' => 'Kompetitif (Atlet)', 'location' => 'Kolam Renang Universitas Lampung'],
            ['day' => 'Jumat', 'time' => '15:00 - 16:30', 'program' => 'Mini Reguler', 'location' => 'Kolam Renang Universitas Lampung'],
            ['day' => 'Sabtu & Minggu', 'time' => '07:00 - 09:00', 'program' => 'Semua Kelas Reguler', 'location' => 'Kolam Renang Universitas Lampung'],
        ];
    }

    private function jadwalRows(): array
    {
        $raw = LandingSetting::get('jadwal_reguler');
        $rows = $raw ? json_decode($raw, true) : [];

        return is_array($rows) && $rows !== [] ? $rows : $this->defaultJadwal();
    }

    public function home()
    {
        return view('welcome', [
            'settings' => LandingSetting::allValues(),
            'jadwalRows' => $this->jadwalRows(),
            'coaches' => LandingCoach::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'programs' => LandingProgram::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'gallery' => LandingGalleryImage::where('is_active', true)->orderBy('sort_order')->orderBy('id')->take(8)->get(),
        ]);
    }

    public function tentang()
    {
        return view('tentang', [
            'settings' => LandingSetting::allValues(),
            'coaches' => LandingCoach::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function program()
    {
        return view('program', [
            'settings' => LandingSetting::allValues(),
            'programs' => LandingProgram::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function galeri()
    {
        return view('galeri', [
            'settings' => LandingSetting::allValues(),
            'gallery' => LandingGalleryImage::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function kontak()
    {
        return view('kontak', [
            'settings' => LandingSetting::allValues(),
        ]);
    }
}
