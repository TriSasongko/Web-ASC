<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/Logo_ASR.png') }}">

        <!-- Fonts: Plus Jakarta Sans & Material Symbols -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            .material-symbols-outlined.filled {
                font-variation-settings: 'FILL' 1;
            }
        </style>
    </head>
    <body class="min-h-screen bg-surface text-on-surface font-body antialiased flex flex-col items-center justify-center p-4 md:p-8">
        <main class="w-full max-w-[480px] bg-surface-container-lowest rounded-xl shadow-[0px_12px_32px_rgba(23,32,51,0.12)] border border-outline-variant/30 overflow-hidden relative z-10 flex flex-col p-8 md:p-10">
            {{ $slot }}
        </main>
    </body>
</html>
