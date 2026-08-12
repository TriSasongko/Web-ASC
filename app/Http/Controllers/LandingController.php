<?php

namespace App\Http\Controllers;

use App\Models\LandingCoach;
use App\Models\LandingGalleryImage;
use App\Models\LandingProgram;
use App\Models\LandingSetting;

class LandingController extends Controller
{
    public function home()
    {
        return view('welcome', [
            'settings' => LandingSetting::allValues(),
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
