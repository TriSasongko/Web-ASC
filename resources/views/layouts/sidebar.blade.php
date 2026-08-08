@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100">
            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:opacity
                class="fixed inset-0 z-30 bg-slate-900/60 lg:hidden" style="display: none;">
            </div>

            <!-- Sidebar -->
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out lg:translate-x-0">
                <!-- Brand -->
                <div class="flex h-16 shrink-0 items-center gap-2 px-6">
                    <x-application-logo class="h-9 w-auto fill-white" />
                    <span class="text-lg font-semibold text-white">ASC Academy</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                    @if (auth()->user()->role === 'admin')
                        @include('layouts.sidebar-menu-admin')
                    @elseif (auth()->user()->role === 'pelatih')
                        @include('layouts.sidebar-menu-pelatih')
                    @else
                        @include('layouts.sidebar-menu-orangtua')
                    @endif
                </nav>

                <!-- User -->
                <div class="shrink-0 border-t border-slate-800 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-700 text-sm font-semibold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="truncate text-xs text-slate-400">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <a href="{{ route('profile.edit') }}" class="rounded-md bg-slate-800 px-3 py-2 text-center text-xs font-medium text-slate-200 hover:bg-slate-700">
                            Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full rounded-md bg-slate-800 px-3 py-2 text-xs font-medium text-slate-200 hover:bg-slate-700">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main -->
            <div class="flex min-h-screen flex-col lg:pl-64">
                <!-- Top bar -->
                <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:px-6">
                    <button @click="sidebarOpen = ! sidebarOpen" class="text-gray-500 hover:text-gray-700 lg:hidden">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1">
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-1 rounded-md border border-transparent px-3 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-700 focus:outline-none">
                                <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </header>

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
