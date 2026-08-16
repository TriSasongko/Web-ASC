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
        @else
            @vite(['resources/js/orangtua.js'])
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
    <body class="antialiased bg-surface text-on-surface font-body">
        <div x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === '1' }" class="flex h-screen overflow-hidden">

            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:opacity
                class="fixed inset-0 z-40 bg-on-surface/60 md:hidden" style="display: none;">
            </div>

            <!-- Sidebar -->
            <aside :class="{
                    'translate-x-0': sidebarOpen,
                    '-translate-x-full': !sidebarOpen,
                    'md:translate-x-0': !sidebarCollapsed,
                    'md:-translate-x-full': sidebarCollapsed,
                }"
                class="fixed top-0 left-0 z-50 flex flex-col h-screen px-4 py-8 transition-transform duration-300 ease-in-out border-r w-sidebar_width bg-surface border-outline-variant/30 md:flex">
                <!-- Brand -->
                <div class="flex items-center gap-4 px-4 mb-10">
                    <img src="{{ asset('images/Logo_ASR.png') }}" alt="Antasena SC"
                        class="object-contain w-14 h-14 shrink-0">
                    <div>
                        <h1 class="font-extrabold font-headline text-headline-md text-primary">Antasena SC</h1>
                        <p class="font-label-sm text-label-sm text-outline">Elite Performance</p>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <nav class="flex-1 pr-2 space-y-2 overflow-y-auto">
                    @include('layouts.'.$menu)
                </nav>

                <!-- Footer Tabs -->
                <div class="pt-6 mt-auto space-y-2 border-t border-outline-variant/30">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 transition-colors rounded-lg text-on-surface-variant hover:bg-surface-container-low group">
                        <span class="transition-colors material-symbols-outlined text-outline group-hover:text-primary">settings</span>
                        <span class="font-label-md text-label-md">Pengaturan</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full gap-3 px-4 py-3 transition-colors rounded-lg text-on-surface-variant hover:bg-surface-container-low group">
                            <span class="transition-colors material-symbols-outlined text-outline group-hover:text-error">logout</span>
                            <span class="font-label-md text-label-md">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Wrapper -->
            <main :class="!sidebarCollapsed ? 'md:ml-sidebar_width' : ''" class="flex flex-col flex-1 h-screen overflow-y-auto bg-surface-bright">
                <!-- TopAppBar -->
                <header class="sticky top-0 z-30 w-full border-b bg-surface/80 backdrop-blur-md border-outline-variant/30">
                    <div class="flex items-center justify-between w-full h-16 gap-3 mx-auto px-gutter max-w-container_max_width">
                        <!-- Mobile Menu Button -->
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 md:hidden text-on-surface shrink-0">
                            <span class="material-symbols-outlined">menu</span>
                        </button>

                        <!-- Desktop Collapse Button -->
                        <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed ? '1' : '0')"
                            class="items-center justify-center hidden p-2 -ml-2 transition-colors rounded-lg md:inline-flex text-on-surface shrink-0 hover:bg-surface-container-low" title="Tutup / buka sidebar">
                            <span class="material-symbols-outlined" :class="sidebarCollapsed ? 'menu' : 'menu_open'">menu_open</span>
                        </button>

                        <!-- Page Title -->
                        @isset($header)
                            <div class="hidden min-w-0 truncate md:block shrink-0">
                                {{ $header }}
                            </div>
                        @endisset

                        <!-- Search Bar (Admin only) -->
                        @if ($role === 'admin')
                            <form action="{{ route('admin.students.index') }}" method="GET" class="flex-1 hidden max-w-md sm:block">
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                                    <input name="search" type="text"
                                        class="w-full py-2 pl-10 pr-4 transition-all border rounded-full shadow-sm bg-surface-container-low border-outline-variant/50 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary font-body-sm text-body-sm"
                                        placeholder="Cari siswa, kelas, atau pelatih...">
                                </div>
                            </form>
                        @endif

                        <!-- Actions & Profile -->
                        <div class="flex items-center gap-4 ml-auto shrink-0">
                            @if ($role === 'admin')
                                <button class="relative p-2 transition-colors rounded-full text-outline hover:text-primary hover:bg-surface-container-low" title="Notifikasi">
                                    <span class="material-symbols-outlined">notifications</span>
                                    @if ($navPendingRegistrations > 0)
                                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full ring-2 ring-surface"></span>
                                    @endif
                                </button>
                                <div class="hidden w-px h-8 bg-outline-variant/30 sm:block"></div>
                            @endif
                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = ! open" class="flex items-center gap-3 p-1 transition-colors rounded-full hover:bg-surface-container-low group">
                                    @if (Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                                            class="object-cover w-8 h-8 border rounded-full border-outline-variant/30">
                                    @else
                                        <div class="flex items-center justify-center w-8 h-8 border rounded-full bg-primary-container text-on-primary font-label-md text-label-md border-outline-variant/30">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="hidden mr-2 text-left sm:block">
                                        <p class="transition-colors font-label-sm text-label-sm text-on-surface group-hover:text-primary">{{ Auth::user()->name }}</p>
                                        <p class="font-label-sm text-label-sm text-outline text-[10px]">{{ $roleLabel }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-outline hidden sm:block text-[20px]">expand_more</span>
                                </button>

                                <div x-show="open" @click.outside="open = false" x-transition
                                    class="absolute right-0 z-50 w-48 py-2 mt-2 border shadow-lg bg-surface-container-lowest border-outline-variant/30 rounded-xl" style="display: none;">
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center gap-2 px-4 py-2 transition-colors text-body-sm text-on-surface hover:bg-surface-container-low">
                                        <span class="material-symbols-outlined text-[18px] text-outline">person</span>
                                        Profil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full gap-2 px-4 py-2 transition-colors text-body-sm text-on-surface hover:bg-surface-container-low">
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
                <div class="flex-1 w-full mx-auto p-margin_mobile md:p-margin_desktop max-w-container_max_width">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
