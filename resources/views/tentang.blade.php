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
            <section class="py-16 overflow-hidden md:py-24 bg-surface" id="coach">
                <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
                    <div class="mb-10 text-center md:mb-14">
                        <span class="font-bold tracking-wider uppercase text-orange font-headline text-label-md">Tim Profesional</span>
                        <h2 class="mt-1 font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">Temui Coach Kami</h2>
                        <p class="max-w-2xl mx-auto mt-3 text-on-surface-variant font-body text-body-lg">Dilatih langsung oleh para profesional bersertifikat yang berdedikasi tinggi.</p>
                    </div>

                    @if ($coaches->isNotEmpty())
                        <div class="relative group/slider" x-data="{
                            active: 0,
                            total: {{ $coaches->count() }},
                            timer: null,
                            scrollTo(index) {
                                this.active = index;
                                const el = this.$refs.slider;
                                const targetCard = el.children[index];
                                if (targetCard) {
                                    const scrollPos = targetCard.offsetLeft - (el.clientWidth / 2) + (targetCard.clientWidth / 2);
                                    el.scrollTo({ left: scrollPos, behavior: 'smooth' });
                                }
                            },
                            next() {
                                this.scrollTo(this.active >= this.total - 1 ? 0 : this.active + 1);
                            },
                            prev() {
                                this.scrollTo(this.active <= 0 ? this.total - 1 : this.active - 1);
                            },
                            play() {
                                if (this.timer) return;
                                this.timer = setInterval(() => this.next(), 4000);
                            },
                            pause() {
                                clearInterval(this.timer);
                                this.timer = null;
                            },
                            updateActiveOnScroll() {
                                const el = this.$refs.slider;
                                const center = el.scrollLeft + (el.clientWidth / 2);
                                let closestIndex = 0;
                                let minDistance = Infinity;

                                Array.from(el.children).forEach((child, i) => {
                                    const childCenter = child.offsetLeft + (child.clientWidth / 2);
                                    const distance = Math.abs(center - childCenter);
                                    if (distance < minDistance) {
                                        minDistance = distance;
                                        closestIndex = i;
                                    }
                                });
                                this.active = closestIndex;
                            },
                            init() {
                                this.play();
                                this.$nextTick(() => this.scrollTo(0));
                            }
                        }" @mouseenter="pause()" @mouseleave="play()">

                            <!-- Slider Track -->
                            <div x-ref="slider" @scroll.debounce.50ms="updateActiveOnScroll()"
                                class="flex gap-4 md:gap-8 overflow-x-auto snap-x snap-mandatory py-12 px-[15vw] md:px-[35vw] no-scrollbar scroll-smooth items-center">
                                @foreach ($coaches as $index => $coach)
                                    <div class="shrink-0 w-[270px] sm:w-[320px] md:w-[360px] snap-center transition-all duration-500 transform"
                                         :class="active === {{ $index }} ? 'scale-105 z-20 opacity-100' : 'scale-90 opacity-50 z-10 blur-[0.5px]'">

                                        <div class="flex flex-col h-full overflow-hidden transition-all duration-500 border bg-surface-container-lowest rounded-3xl group border-outline-variant/30"
                                             :class="active === {{ $index }} ? 'shadow-2xl ring-2 ring-primary/20' : 'shadow-md'">

                                            <div class="relative overflow-hidden aspect-[4/5] bg-surface-container">
                                                @if ($coach->photo)
                                                    <img alt="{{ $coach->name }}"
                                                        class="object-cover w-full h-full transition-transform duration-700 ease-out group-hover:scale-108"
                                                        src="{{ $coach->photo }}">
                                                @else
                                                    <div class="flex flex-col items-center justify-center w-full h-full text-on-surface-variant/40">
                                                        <span class="material-symbols-outlined text-[72px]">person</span>
                                                    </div>
                                                @endif

                                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent opacity-90"></div>

                                                <!-- Badge & Info Overlay -->
                                                <div class="absolute text-white bottom-5 left-5 right-5">
                                                    <span class="inline-block px-3 py-1 mb-2 font-semibold text-white rounded-full shadow-md bg-orange/90 backdrop-blur-md font-body text-label-sm">
                                                        {{ $coach->position }}
                                                    </span>
                                                    <h3 class="font-bold leading-tight text-white font-headline text-headline-sm md:text-headline-md drop-shadow-md">
                                                        {{ $coach->name }}
                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="flex flex-col justify-between flex-grow p-6 bg-surface-container-lowest">
                                                <p class="leading-relaxed text-on-surface-variant text-body-sm line-clamp-3">
                                                    {{ $coach->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Controls -->
                            <button type="button" @click="prev()" aria-label="Coach sebelumnya"
                                class="absolute z-30 flex items-center justify-center w-12 h-12 transition-all duration-300 -translate-y-1/2 border rounded-full shadow-2xl left-4 md:left-12 top-1/2 bg-surface/90 text-primary backdrop-blur-md border-outline-variant/40 hover:bg-primary hover:text-white hover:scale-110">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <button type="button" @click="next()" aria-label="Coach berikutnya"
                                class="absolute z-30 flex items-center justify-center w-12 h-12 transition-all duration-300 -translate-y-1/2 border rounded-full shadow-2xl right-4 md:right-12 top-1/2 bg-surface/90 text-primary backdrop-blur-md border-outline-variant/40 hover:bg-primary hover:text-white hover:scale-110">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>

                            <!-- Indicators -->
                            <div class="flex items-center justify-center gap-2 mt-4">
                                <template x-for="(item, index) in total" :key="index">
                                    <button @click="scrollTo(index)"
                                            class="h-2.5 rounded-full transition-all duration-300"
                                            :class="active === index ? 'w-8 bg-orange' : 'w-2.5 bg-outline-variant/50 hover:bg-outline-variant'">
                                    </button>
                                </template>
                            </div>
                        </div>
                    @else
                        <p class="py-8 text-center text-on-surface-variant">Belum ada coach.</p>
                    @endif
                </div>
            </section>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
