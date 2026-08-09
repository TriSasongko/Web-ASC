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
    <body class="font-sans antialiased bg-surface">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 sm:py-0">
            <div class="w-full sm:max-w-md">
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex items-center gap-3">
                        <span class="w-12 h-12 rounded-xl bg-primary-container text-on-primary flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-[28px]">sports_soccer</span>
                        </span>
                        <span class="font-headline text-headline-md text-on-surface">ASC Academy</span>
                    </a>
                </div>

                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-[0px_8px_32px_rgba(23,32,51,0.08)] px-6 py-8 sm:px-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
