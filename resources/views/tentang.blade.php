<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tentang Kami - AantassenaSwimClub</title>
        <meta name="description" content="Kenali lebih dekat AantassenaSwimClub. Sejarah, visi misi, dan tim coach berpengalaman yang membimbing atlet renang muda berprestasi.">
        <meta property="og:title" content="Tentang Kami - AantassenaSwimClub">
        <meta property="og:description" content="Kenali lebih dekat AantassenaSwimClub. Sejarah, visi misi, dan tim coach berpengalaman yang membimbing atlet renang muda berprestasi.">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ asset('images/Logo_ASR.png') }}">
        <meta property="og:url" content="{{ url('/tentang') }}">
        <link rel="canonical" href="{{ url('/tentang') }}">
        <link rel="icon" type="image/png" href="{{ asset('images/Logo_ASR.png') }}">

        <!-- Fonts: Manrope & Material Symbols -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif;
            }
            .font-body {
                font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif;
            }
            .font-headline {
                font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif;
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
                    <div>
                        <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-6">{{ $settings['tentang_heading'] }}</h1>
                        <p class="font-body text-body-md md:text-body-lg text-on-surface-variant mb-6">{{ $settings['tentang_text'] }}</p>
                        <div class="inline-flex items-center gap-4 bg-surface-container-low rounded-xl border-l-4 border-orange p-4 md:p-5">
                            <span class="font-headline text-headline-md md:text-headline-xl text-orange font-bold">{{ $settings['tentang_years'] }}</span>
                            <span class="font-body text-body-sm md:text-body-md text-on-surface-variant">{{ $settings['tentang_years_label'] }}</span>
                        </div>
                    </div>
                    <div class="rounded-xl overflow-hidden pool-shadow">
                        <img class="w-full h-64 md:h-[400px] object-cover"
                             alt="Perenang profesional saat latihan di kolam renang"
                             src="{{ $settings['tentang_image'] }}">
                    </div>
                </div>
            </section>

            <!-- Visi Misi Section -->
            <section class="bg-surface-container-low py-16 md:py-24">
                <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
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
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary text-center mb-8 md:mb-12">Tim Pelatih Profesional Kami</h2>
                @if ($coaches->isNotEmpty())
                    <div class="relative"
                         x-data="{
                             index: 0,
                             startX: null,
                             timer: null,
                             step() {
                                 const t = this.$refs.track;
                                 const card = t.querySelector(':scope > *');
                                 const gap = parseFloat(getComputedStyle(t).gap) || 0;
                                 return card ? card.offsetWidth + gap : 0;
                             },
                             maxIndex() {
                                 const t = this.$refs.track;
                                 const s = this.step();
                                 return s > 0 ? Math.round((t.scrollWidth - t.clientWidth) / s) : 0;
                             },
                             slideTo(i) {
                                 const s = this.step();
                                 const max = this.maxIndex();
                                 this.index = i > max ? 0 : (i < 0 ? max : i);
                                 this.$refs.track.style.transform = `translateX(-${this.index * s}px)`;
                             },
                             play() {
                                 if (this.timer) return;
                                 this.timer = setInterval(() => {
                                     if (!this.startX) this.slideTo(this.index + 1);
                                 }, 3000);
                             },
                             pause() {
                                 clearInterval(this.timer);
                                 this.timer = null;
                             },
                             restart() {
                                 clearInterval(this.timer);
                                 this.timer = null;
                                 this.play();
                             },
                             prev() {
                                 this.slideTo(this.index - 1);
                                 this.restart();
                             },
                             next() {
                                 this.slideTo(this.index + 1);
                                 this.restart();
                             },
                             touchStart(e) {
                                 this.startX = e.changedTouches[0].clientX;
                             },
                             touchEnd(e) {
                                 if (this.startX === null) return;
                                 const dx = e.changedTouches[0].clientX - this.startX;
                                 this.startX = null;
                                 if (Math.abs(dx) >= 40) this.slideTo(this.index + (dx < 0 ? 1 : -1));
                                 this.restart();
                             },
                             init() {
                                 this.play();
                                 window.addEventListener('resize', () => this.slideTo(this.index));
                             }
                         }"
                         @mouseenter="pause()"
                         @mouseleave="play()">
                        <div class="overflow-hidden"
                             @touchstart.passive="touchStart($event)"
                             @touchend.passive="touchEnd($event)">
                            <div x-ref="track"
                                 class="flex gap-6 transition-transform duration-700 ease-in-out will-change-transform">
                                @foreach ($coaches as $coach)
                                    <div class="shrink-0 w-[85%] sm:w-[48%] lg:w-[31.5%]">
                                        <div class="relative h-full bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md group aspect-[3/4]">
                                            @if ($coach->photo)
                                                <img alt="{{ $coach->name }}"
                                                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                                     src="{{ $coach->photo }}">
                                            @else
                                                <div class="absolute inset-0 bg-surface-container flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-on-surface-variant text-[64px]">person</span>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                                            <div class="absolute inset-x-0 bottom-0 p-4 md:p-5">
                                                <p class="text-orange-lighter font-body text-label-md font-semibold mb-1">{{ $coach->position }}</p>
                                                <h3 class="text-white font-headline text-headline-sm font-bold">{{ $coach->name }}</h3>
                                                <p class="text-white/85 text-body-sm mt-2 line-clamp-3 max-h-24 opacity-100 transition-all duration-300 md:max-h-0 md:opacity-0 md:group-hover:max-h-24 md:group-hover:opacity-100">{{ $coach->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" @click="prev()" aria-label="Coach sebelumnya"
                            class="hidden md:flex absolute -left-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-primary text-on-primary items-center justify-center shadow-lg hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button type="button" @click="next()" aria-label="Coach berikutnya"
                            class="hidden md:flex absolute -right-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-primary text-on-primary items-center justify-center shadow-lg hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                @else
                    <p class="text-center text-on-surface-variant">Belum ada coach.</p>
                @endif
            </section>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
