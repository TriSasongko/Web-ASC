<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @php
        $brandImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgoElsg3gSYXw9da-q5t6qChRYHfEGfWfcsS88HbQvQgJPYpOG_OM_mrAN0vYbwSso5SXWRmIygeoCDjAx8eGTIuLO2M16P_DiK7yAOI2c3OZMoaClv8RX1Y3f95vwNMqwmO6eV-BUBoDfKFAxIS4CnzGkAa8d_DqmcWIDaZ4bmz7Eh4QKSgErzfGV3sn7Pks4H-KG_ysda2jZKz5FOePc4xATyOjA3_a0ApDaNn80C33e8kPRYbOi';
    @endphp

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
    <body class="bg-background text-on-surface font-body antialiased min-h-screen flex items-center justify-center p-0 md:p-gutter">
        <main class="w-full h-screen md:h-[calc(100vh-48px)] md:max-h-[900px] max-w-container_max_width mx-auto bg-surface-container-lowest md:rounded-2xl overflow-hidden shadow-[0px_12px_32px_rgba(23,32,51,0.12)] flex flex-col md:flex-row">
            <!-- Left Side: Branding & Imagery -->
            <section class="relative hidden md:flex flex-col justify-between w-1/2 h-full p-12 overflow-hidden bg-primary">
                <!-- Background Image -->
                <div class="absolute inset-0 z-0" style="background-image: url('{{ $brandImage }}'); background-size: cover; background-position: center;"></div>
                <!-- Aqua Gradient Overlay -->
                <div class="absolute inset-0 z-10 bg-gradient-to-t from-primary/90 via-primary-container/60 to-transparent mix-blend-multiply"></div>
                <div class="absolute inset-0 z-10 bg-gradient-to-br from-secondary-container/30 to-transparent"></div>
                <!-- Content -->
                <div class="relative z-20 flex flex-col h-full justify-between">
                    <!-- Logo Area -->
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/Logo_ASR.png') }}" alt="ANTASENA SC" class="h-14 w-auto drop-shadow-lg">
                        <h1 class="font-headline text-headline-md text-on-primary">ANTASENA SC</h1>
                    </div>
                    <!-- Slogan Area -->
                    <div class="max-w-md">
                        <h2 class="font-headline text-headline-xl text-on-primary mb-4 leading-tight">Berenang Lebih Percaya Diri. <br/>Tumbuh Lebih Hebat.</h2>
                        <p class="font-body text-body-lg text-on-primary/90">Manajemen akademi renang profesional untuk pelatih, orang tua, dan atlet masa depan.</p>
                    </div>
                </div>
            </section>

            <!-- Right Side: Auth Content -->
            <section class="w-full md:w-1/2 h-full flex flex-col px-6 md:px-16 lg:px-24 bg-surface-container-lowest relative overflow-y-auto">
                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="md:hidden flex items-center gap-2 pt-6 mb-10 justify-center shrink-0">
                    <img src="{{ asset('images/Logo_ASR.png') }}" alt="ANTASENA SC" class="h-12 w-auto rounded-xl bg-primary p-1.5">
                    <h1 class="font-headline text-headline-md text-primary">ANTASENA SC</h1>
                </div>
                <div class="w-full max-w-sm mx-auto my-auto py-8 md:py-0">
                    {{ $slot }}
                </div>
            </section>
        </main>
    </body>
</html>
