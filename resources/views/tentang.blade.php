<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tentang Kami - Antasena Swimming Club</title>

        <!-- Fonts: Montserrat, Inter & Material Symbols -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Montserrat:wght@100..900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            }
            .font-body {
                font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            }
            .font-headline {
                font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;
            }
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            .material-symbols-outlined.filled {
                font-variation-settings: 'FILL' 1;
            }
            .pool-shadow {
                box-shadow: 0 10px 30px -10px rgba(0, 71, 169, 0.08);
            }
            html {
                scroll-behavior: smooth;
            }
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="bg-background text-on-background font-body text-body-md antialiased min-h-screen flex flex-col pt-24">

        <!-- Navbar -->
        @include('partials.header')

        <main class="flex-grow">
            @php
                $tentangMisi = collect(explode("\n", $settings['tentang_misi'] ?? ''))->map(fn ($line) => trim($line))->filter()->values();
            @endphp
            <!-- Hero Section -->
            <section class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop py-16 md:py-24">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-6">{{ $settings['tentang_heading'] }}</h1>
                        <p class="font-body text-body-lg text-on-surface-variant mb-6">{{ $settings['tentang_text'] }}</p>
                        <div class="flex items-center gap-4">
                            <span class="font-headline text-headline-xl text-orange font-bold">{{ $settings['tentang_years'] }}</span>
                            <span class="font-body text-body-md text-on-surface-variant">{{ $settings['tentang_years_label'] }}</span>
                        </div>
                    </div>
                    <div class="rounded-xl overflow-hidden pool-shadow">
                        <img class="w-full h-[400px] object-cover"
                             alt="Perenang profesional saat latihan di kolam renang"
                             src="{{ $settings['tentang_image'] }}">
                    </div>
                </div>
            </section>

            <!-- Visi Misi Section -->
            <section class="bg-surface-container-low py-16 md:py-24">
                <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="bg-surface p-6 rounded-xl pool-shadow border border-outline/10">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-symbols-outlined text-orange text-[32px]">visibility</span>
                                <h2 class="font-headline text-headline-md text-primary">Visi</h2>
                            </div>
                            <p class="font-body text-body-md text-on-surface-variant">{{ $settings['tentang_visi'] }}</p>
                        </div>
                        <div class="bg-surface p-6 rounded-xl pool-shadow border border-outline/10">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-symbols-outlined text-orange text-[32px]">flag</span>
                                <h2 class="font-headline text-headline-md text-primary">Misi</h2>
                            </div>
                            <ul class="list-disc list-inside font-body text-body-md text-on-surface-variant space-y-2">
                                @forelse ($tentangMisi as $misi)
                                    <li>{{ $misi }}</li>
                                @empty
                                    <li>Menyediakan program pelatihan yang aman, terstruktur, dan menyenangkan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tim Pelatih Section -->
            <section class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop py-16 md:py-24">
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary text-center mb-12">Tim Pelatih Profesional Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse ($coaches as $coach)
                        <div class="bg-surface rounded-xl pool-shadow border border-outline/10 overflow-hidden flex flex-col">
                            @if ($coach->photo_url)
                                <img class="w-full h-64 object-cover"
                                     alt="{{ $coach->name }}, {{ $coach->position }}"
                                     src="{{ $coach->photo_url }}">
                            @else
                                <div class="w-full h-64 bg-surface-container-low flex items-center justify-center">
                                    <span class="material-symbols-outlined text-on-surface-variant text-[64px]">person</span>
                                </div>
                            @endif
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="font-headline text-headline-sm text-primary mb-1">{{ $coach->name }}</h3>
                                    <p class="font-body text-label-md text-orange mb-4">{{ $coach->position }}</p>
                                    <p class="font-body text-body-md text-on-surface-variant">{{ $coach->description }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-on-surface-variant">Belum ada coach.</p>
                    @endforelse
                </div>
            </section>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
