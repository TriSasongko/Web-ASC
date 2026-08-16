<?php

namespace App\Providers;

use App\Models\Registration;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <-- Ditambahkan
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Solusi untuk error: 1071 Specified key was too long
        Schema::defaultStringLength(191);

        View::composer('layouts.sidebar', function ($view) {
            $role = Auth::check() ? Auth::user()->role : null;

            $view->with('sidebarClasses', $role === 'admin' || $role === 'pelatih'
                ? SchoolClass::where('is_active', true)
                    ->orderBy('name')
                    ->get()
                : collect());

            $view->with('navPendingRegistrations', $role === 'admin'
                ? Registration::where('status', 'menunggu_verifikasi')->count()
                : 0);

            $view->with('navClassesPending', $role === 'admin'
                ? DB::table('class_student')
                    ->join('classes', 'classes.id', '=', 'class_student.class_id')
                    ->join('programs', 'programs.id', '=', 'classes.program_id')
                    ->where('programs.billing_type', 'per_paket')
                    ->whereNotNull('programs.total_sessions')
                    ->whereColumn('class_student.sessions_completed', '>=', DB::raw('programs.total_sessions - 1'))
                    ->where('class_student.renewal_status', '!=', 'berhenti')
                    ->distinct()
                    ->count('classes.id')
                : 0);

            $view->with('navRenewalsPending', $role === 'admin'
                ? DB::table('class_student')
                    ->where('is_active', true)
                    ->where('renewal_status', 'perlu_konfirmasi')
                    ->count()
                : 0);
        });
    }
}
