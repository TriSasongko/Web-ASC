<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pelatih.dashboard');
    }
}
