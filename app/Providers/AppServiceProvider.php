<?php

namespace App\Providers;

use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
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
        View::composer('layouts.sidebar', function ($view) {
            $view->with('sidebarClasses', Auth::check() && Auth::user()->role === 'pelatih'
                ? SchoolClass::where('coach_id', Auth::id())
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get()
                : collect());
        });
    }
}
