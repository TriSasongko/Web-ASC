<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Program Pelatihan - Antasena Swimming Club</title>

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
                box-shadow: 0 10px 25px -5px rgba(0, 71, 169, 0.08);
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

        <!-- Main Content -->
        <main class="flex-grow pb-16 md:pb-24 px-margin_mobile md:px-margin_desktop max-w-container_max_width mx-auto w-full">
            <!-- Hero -->
            <section class="relative text-center pt-8 md:pt-14 pb-12 md:pb-16 overflow-hidden">
                <div class="absolute inset-0 -z-10 pointer-events-none">
                    <div class="absolute -top-20 -right-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-10 -left-24 w-80 h-80 bg-orange/10 rounded-full blur-3xl"></div>
                </div>
                <span class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full font-body text-label-md font-semibold mb-5">
                    <span class="material-symbols-outlined text-[18px]">pool</span>
                    Program Pelatihan
                </span>
                <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-4">{{ $settings['program_heading'] }}</h1>
                <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">{{ $settings['program_subtitle'] }}</p>
            </section>

            <!-- Programs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($programs as $program)
                    <div class="bg-surface-container-lowest rounded-2xl border p-6 flex flex-col hover:shadow-xl transition-shadow {{ $program->featured ? 'border-primary/40 ring-2 ring-primary/20 shadow-lg' : 'border-outline-variant/30 shadow-md' }}">
                        <div class="flex items-start justify-between gap-2 mb-4">
                            <div class="w-11 h-11 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined filled">school</span>
                            </div>
                            @if ($program->featured)
                                <span class="shrink-0 bg-primary text-on-primary text-[10px] font-bold px-2.5 py-1 rounded-full mt-0.5 inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">star</span>
                                    UNGGULAN
                                </span>
                            @elseif ($program->badge)
                                <span class="shrink-0 bg-orange text-white text-[10px] font-bold px-2.5 py-1 rounded-full mt-0.5">{{ $program->badge }}</span>
                            @endif
                        </div>
                        <h2 class="font-headline text-headline-sm text-primary font-bold mb-1">{{ $program->name }}</h2>
                        @if ($program->subtitle)
                            <p class="text-on-surface-variant text-label-sm mb-4">{{ $program->subtitle }}</p>
                        @endif
                        <div class="mb-6 flex items-baseline gap-2 flex-wrap">
                            @if ($program->price)
                                <span class="font-headline text-headline-lg text-orange font-bold">Rp{{ number_format($program->price, 0, ',', '.') }}</span>
                                <span class="text-on-surface-variant text-body-md">{{ $program->billing_unit }}</span>
                            @else
                                <span class="font-headline text-headline-lg text-orange font-bold">Hubungi Kami</span>
                            @endif
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            @foreach ($program->featureList() as $feature)
                                <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                    <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('register') }}"
                            class="w-full text-center bg-primary text-on-primary hover:bg-primary-container py-2.5 rounded-lg font-body text-label-md transition-colors mt-auto">
                            {{ $program->button_label }}
                        </a>
                    </div>
                @empty
                    <p class="col-span-full text-center text-on-surface-variant">Belum ada program.</p>
                @endforelse
            </div>

            <!-- CTA Banner -->
            <section class="mt-16 md:mt-24">
                <div class="relative overflow-hidden rounded-3xl bg-primary text-on-primary px-6 md:px-12 py-10 md:py-14 text-center shadow-xl shadow-primary/20">
                    <div class="absolute -top-16 -left-16 w-64 h-64 bg-orange/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-72 h-72 bg-surface/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <h2 class="font-headline text-headline-lg-mobile md:text-headline-xl font-bold mb-3">Belum yakin program mana yang tepat?</h2>
                        <p class="text-on-primary/90 text-body-md md:text-body-lg max-w-2xl mx-auto mb-8">Konsultasikan kebutuhan dan target Anda bersama coach kami. Kami akan bantu memilih program yang paling sesuai dengan tujuan berenang Anda.</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ \App\Models\User::adminWaLink() }}" target="_blank"
                                class="bg-orange text-white px-8 py-3.5 rounded-lg font-body text-label-md hover:bg-orange-light transition-colors shadow-lg shadow-orange/30 inline-flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">chat</span>
                                Konsultasi Gratis
                            </a>
                            <a href="{{ url('/') }}#jadwal"
                                class="bg-surface/10 backdrop-blur-sm border-2 border-on-primary text-on-primary px-8 py-3.5 rounded-lg font-body text-label-md hover:bg-surface/20 transition-colors">
                                Lihat Jadwal Latihan
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
