@props(['header' => null])

@php
    $role = auth()->user()->role;
    $menu = match ($role) {
        'admin' => 'sidebar-menu-admin',
        'pelatih' => 'sidebar-menu-pelatih',
        default => 'sidebar-menu-orangtua',
    };
    $roleLabel = match ($role) {
        'admin' => 'Administrator',
        'pelatih' => 'Pelatih',
        default => 'Orang Tua',
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts: Plus Jakarta Sans & Material Symbols -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if ($role === 'admin')
            @vite(['resources/js/admin.js'])
        @elseif ($role === 'pelatih')
            @vite(['resources/js/pelatih.js'])
        @endif

        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            .material-symbols-outlined.filled {
                font-variation-settings: 'FILL' 1;
            }

            /* Custom scrollbar for webkit */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f3ff;
            }
            ::-webkit-scrollbar-thumb {
                background: #c2c6d6;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #737785;
            }
        </style>
    </head>
    <body class="bg-surface text-on-surface font-body antialiased">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:opacity
                class="fixed inset-0 z-40 bg-on-surface/60 md:hidden" style="display: none;">
            </div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed left-0 top-0 z-50 h-screen w-sidebar_width bg-surface flex flex-col py-8 px-4 border-r border-outline-variant/30 transition-transform duration-300 ease-in-out md:translate-x-0 md:flex">
                <!-- Brand -->
                <div class="flex items-center gap-4 px-4 mb-10">
                    <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-on-primary font-bold text-xl shadow-md">
                        A
                    </div>
                    <div>
                        <h1 class="font-headline text-headline-md font-extrabold text-primary">ASC Academy</h1>
                        <p class="font-label-sm text-label-sm text-outline">Elite Performance</p>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <nav class="flex-1 space-y-2 overflow-y-auto pr-2">
                    @include('layouts.'.$menu)
                </nav>

                <!-- Footer Tabs -->
                <div class="mt-auto pt-6 border-t border-outline-variant/30 space-y-2">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group">
                        <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors">settings</span>
                        <span class="font-label-md text-label-md">Pengaturan</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors group">
                            <span class="material-symbols-outlined text-outline group-hover:text-error transition-colors">logout</span>
                            <span class="font-label-md text-label-md">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Wrapper -->
            <main class="flex-1 md:ml-sidebar_width h-screen overflow-y-auto flex flex-col bg-surface-bright">
                <!-- TopAppBar -->
                <header class="sticky top-0 z-30 w-full bg-surface/80 backdrop-blur-md border-b border-outline-variant/30">
                    <div class="flex justify-between items-center gap-3 h-16 px-gutter max-w-container_max_width mx-auto w-full">
                        <!-- Mobile Menu Button -->
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-on-surface -ml-2 shrink-0">
                            <span class="material-symbols-outlined">menu</span>
                        </button>

                        <!-- Page Title -->
                        @isset($header)
                            <div class="hidden md:block min-w-0 truncate shrink-0">
                                {{ $header }}
                            </div>
                        @endisset

                        <!-- Search Bar (Admin only) -->
                        @if ($role === 'admin')
                            <form action="{{ route('admin.students.index') }}" method="GET" class="flex-1 max-w-md hidden sm:block">
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                                    <input name="search" type="text"
                                        class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant/50 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary font-body-sm text-body-sm transition-all shadow-sm"
                                        placeholder="Cari siswa, kelas, atau pelatih...">
                                </div>
                            </form>
                        @endif

                        <!-- Actions & Profile -->
                        <div class="flex items-center gap-4 ml-auto shrink-0">
                            @if ($role === 'admin')
                                <button class="relative p-2 text-outline hover:text-primary transition-colors rounded-full hover:bg-surface-container-low" title="Notifikasi">
                                    <span class="material-symbols-outlined">notifications</span>
                                    @if ($navPendingRegistrations > 0)
                                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full ring-2 ring-surface"></span>
                                    @endif
                                </button>
                                <div class="h-8 w-px bg-outline-variant/30 hidden sm:block"></div>
                            @endif
                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = ! open" class="flex items-center gap-3 p-1 rounded-full hover:bg-surface-container-low transition-colors group">
                                    @if (Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                                            class="w-8 h-8 rounded-full object-cover border border-outline-variant/30">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-label-md text-label-md border border-outline-variant/30">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="hidden sm:block text-left mr-2">
                                        <p class="font-label-sm text-label-sm text-on-surface group-hover:text-primary transition-colors">{{ Auth::user()->name }}</p>
                                        <p class="font-label-sm text-label-sm text-outline text-[10px]">{{ $roleLabel }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-outline hidden sm:block text-[20px]">expand_more</span>
                                </button>

                                <div x-show="open" @click.outside="open = false" x-transition
                                    class="absolute right-0 mt-2 w-48 py-2 bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-lg z-50" style="display: none;">
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center gap-2 px-4 py-2 text-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-outline">person</span>
                                        Profil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-2 px-4 py-2 text-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors">
                                            <span class="material-symbols-outlined text-[18px] text-outline">logout</span>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="flex-1 p-margin_mobile md:p-margin_desktop max-w-container_max_width mx-auto w-full">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
