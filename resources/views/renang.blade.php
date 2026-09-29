<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $settings['renang_heading'] }} - AantassenaSwimClub</title>
        <meta name="description" content="Mengenal olahraga renang secara umum dan program renang di AantassenaSwimClub. Dilengkapi video dan FAQ seputar renang.">
        <meta property="og:title" content="{{ $settings['renang_heading'] }} - AantassenaSwimClub">
        <meta property="og:description" content="{{ $settings['renang_subtitle'] }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ asset('images/Logo_ASR.png') }}">
        <meta property="og:url" content="{{ url('/renang') }}">
        <link rel="canonical" href="{{ url('/renang') }}">
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

        @php
            $renangFaqHeading = $settings['renang_faq_heading'] ?? 'Pertanyaan Seputar Renang';
            $renangFaqSubtitle = $settings['renang_faq_subtitle'] ?? '';
            $paragraphsUmum = collect(explode("\n", $settings['renang_umum_text'] ?? ''))->map(fn ($line) => trim($line))->filter()->values();
            $paragraphsKhusus = collect(explode("\n", $settings['renang_khusus_text'] ?? ''))->map(fn ($line) => trim($line))->filter()->values();
        @endphp

        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="relative overflow-hidden bg-surface-container-lowest">
                <div class="absolute inset-0 pointer-events-none -z-10">
                    <div class="absolute rounded-full -top-24 -left-24 w-80 h-80 bg-primary/10 blur-3xl"></div>
                    <div class="absolute rounded-full top-10 -right-24 w-80 h-80 bg-orange/10 blur-3xl"></div>
                    <div class="absolute rounded-full bottom-0 left-1/3 w-96 h-96 bg-secondary/10 blur-3xl"></div>
                    <span class="absolute -top-6 right-10 hidden text-primary/5 lg:block">
                        <span class="material-symbols-outlined text-[220px] filled">swimming</span>
                    </span>
                    <span class="absolute bottom-24 left-8 hidden text-secondary/5 xl:block">
                        <span class="material-symbols-outlined text-[160px]">waves</span>
                    </span>
                </div>

                <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop pt-16 md:pt-24 pb-24 md:pb-32 text-center">
                    <span class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full font-body text-label-md font-semibold mb-6">
                        <span class="material-symbols-outlined text-[18px]">pool</span>
                        Olahraga Air untuk Semua Usia
                    </span>
                    <h1 class="mb-5 font-headline text-headline-lg-mobile md:text-headline-xl text-primary">{{ $settings['renang_heading'] }}</h1>
                    <p class="max-w-3xl mx-auto font-body text-body-md md:text-body-lg text-on-surface-variant">
                        {{ $settings['renang_subtitle'] }}
                    </p>

                    <!-- Quick nav -->
                    <div class="flex flex-wrap justify-center gap-3 mt-10">
                        <a href="#umum" class="inline-flex items-center gap-2 rounded-full border border-outline-variant/40 bg-surface px-5 py-2.5 font-body text-label-md text-on-surface-variant transition-all hover:border-primary hover:text-primary hover:bg-primary/5 active:scale-95">
                            <span class="material-symbols-outlined text-[18px] text-primary">article</span>
                            Penjelasan Umum
                        </a>
                        <a href="#khusus" class="inline-flex items-center gap-2 rounded-full border border-outline-variant/40 bg-surface px-5 py-2.5 font-body text-label-md text-on-surface-variant transition-all hover:border-primary hover:text-primary hover:bg-primary/5 active:scale-95">
                            <span class="material-symbols-outlined text-[18px] text-primary">waves</span>
                            Renang di ASC
                        </a>
                        <a href="#faq" class="inline-flex items-center gap-2 rounded-full border border-outline-variant/40 bg-surface px-5 py-2.5 font-body text-label-md text-on-surface-variant transition-all hover:border-primary hover:text-primary hover:bg-primary/5 active:scale-95">
                            <span class="material-symbols-outlined text-[18px] text-primary">help</span>
                            FAQ
                        </a>
                    </div>
                </div>

                <!-- Wave divider -->
                <svg class="block w-full text-surface-container-low -mb-px" viewBox="0 0 1440 72" fill="currentColor" preserveAspectRatio="none" aria-hidden="true">
                    <path d="M0,40 C240,88 480,0 720,16 C960,32 1200,72 1440,32 L1440,72 L0,72 Z"></path>
                </svg>
            </section>

            <!-- Fakta Singkat Section -->
            <section class="bg-surface-container-low">
                <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop pb-16 md:pb-20">
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
                        <div class="flex flex-col items-center gap-3 rounded-2xl border border-outline-variant/40 bg-surface-container-lowest p-5 md:p-6 pool-shadow text-center">
                            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-[24px]">accessibility_new</span>
                            </span>
                            <div>
                                <p class="font-headline font-bold text-headline-sm text-primary">Low Impact</p>
                                <p class="mt-0.5 font-body text-body-sm text-on-surface-variant">Ramah untuk persendian</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-center gap-3 rounded-2xl border border-outline-variant/40 bg-surface-container-lowest p-5 md:p-6 pool-shadow text-center">
                            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-[24px]">monitor_heart</span>
                            </span>
                            <div>
                                <p class="font-headline font-bold text-headline-sm text-primary">Sehat &amp; Bugar</p>
                                <p class="mt-0.5 font-body text-body-sm text-on-surface-variant">Jantung, napas, dan daya tahan</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-center gap-3 rounded-2xl border border-outline-variant/40 bg-surface-container-lowest p-5 md:p-6 pool-shadow text-center">
                            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-[24px]">fitness_center</span>
                            </span>
                            <div>
                                <p class="font-headline font-bold text-headline-sm text-primary">Seluruh Otot</p>
                                <p class="mt-0.5 font-body text-body-sm text-on-surface-variant">Latihan tubuh paling lengkap</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-center gap-3 rounded-2xl border border-outline-variant/40 bg-surface-container-lowest p-5 md:p-6 pool-shadow text-center">
                            <span class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-[24px]">groups</span>
                            </span>
                            <div>
                                <p class="font-headline font-bold text-headline-sm text-primary">Semua Usia</p>
                                <p class="mt-0.5 font-body text-body-sm text-on-surface-variant">Anak-anak hingga lansia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Penjelasan Umum Section -->
            <section class="scroll-mt-24 bg-surface py-16 md:py-24" id="umum">
                <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                        <div class="lg:col-span-7">
                            <span class="font-bold tracking-wider uppercase text-orange font-headline text-label-md">Penjelasan Umum</span>
                            <h2 class="mt-2 mb-6 font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">{{ $settings['renang_umum_heading'] }}</h2>
                            <div class="space-y-4 text-on-surface-variant font-body text-body-md md:text-body-lg leading-relaxed">
                                @forelse ($paragraphsUmum as $par)
                                    <p>{{ $par }}</p>
                                @empty
                                    <p>{{ $settings['renang_umum_text'] ?? '' }}</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="relative rounded-3xl border border-outline-variant/30 bg-surface-container-lowest p-7 md:p-8 pool-shadow">
                                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-secondary-container to-primary rounded-t-3xl"></div>
                                <div class="flex items-center gap-3 mb-6">
                                    <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange/10 text-orange">
                                        <span class="material-symbols-outlined text-[22px]">thumb_up</span>
                                    </span>
                                    <h3 class="font-headline font-bold text-headline-sm text-primary">Kenapa Harus Berenang?</h3>
                                </div>
                                <ul class="space-y-5">
                                    <li class="flex items-start gap-4">
                                        <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[22px]">accessibility_new</span>
                                        </span>
                                        <div>
                                            <p class="font-body font-bold text-body-md text-on-surface">Menggerakkan seluruh tubuh</p>
                                            <p class="font-body text-body-sm text-on-surface-variant">Latihan menyeluruh tanpa membebani persendian.</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[22px]">monitor_heart</span>
                                        </span>
                                        <div>
                                            <p class="font-body font-bold text-body-md text-on-surface">Menyehatkan jantung &amp; napas</p>
                                            <p class="font-body text-body-sm text-on-surface-variant">Melatih sistem pernapasan dan daya tahan tubuh.</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[22px]">spa</span>
                                        </span>
                                        <div>
                                            <p class="font-body font-bold text-body-md text-on-surface">Olahraga low impact</p>
                                            <p class="font-body text-body-sm text-on-surface-variant">Aman untuk persendian, cocok untuk kenyamanan tubuh.</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <span class="flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[22px]">groups</span>
                                        </span>
                                        <div>
                                            <p class="font-body font-bold text-body-md text-on-surface">Cocok untuk semua usia</p>
                                            <p class="font-body text-body-sm text-on-surface-variant">Dari anak-anak hingga lansia dapat berlatih dengan aman.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Penjelasan Khusus Section (Spotlight ASC) -->
            <section class="scroll-mt-24 bg-surface-container-low py-16 md:py-24" id="khusus">
                <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
                    <div class="relative overflow-hidden rounded-3xl border border-outline-variant/30 bg-surface-container-lowest p-7 md:p-12 pool-shadow">
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute rounded-full -top-20 -right-20 w-72 h-72 bg-primary/10 blur-3xl"></div>
                            <span class="absolute -bottom-8 -left-4 text-primary/5">
                                <span class="material-symbols-outlined text-[180px] filled">pool</span>
                            </span>
                        </div>

                        <div class="relative grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                            <div class="lg:col-span-5 lg:order-2">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3 rounded-2xl border border-outline-variant/40 bg-surface p-4">
                                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary/10 text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[22px]">school</span>
                                        </span>
                                        <div>
                                            <p class="font-headline font-bold text-headline-sm text-primary">Kurikulum Bertingkat</p>
                                            <p class="font-body text-body-sm text-on-surface-variant">Dari pemula hingga atlet</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 rounded-2xl border border-outline-variant/40 bg-surface p-4">
                                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary/10 text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[22px]">verified_user</span>
                                        </span>
                                        <div>
                                            <p class="font-headline font-bold text-headline-sm text-primary">Coach Bersertifikat</p>
                                            <p class="font-body text-body-sm text-on-surface-variant">Pendampingan terbaik</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 rounded-2xl border border-outline-variant/40 bg-surface p-4">
                                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary/10 text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[22px]">description</span>
                                        </span>
                                        <div>
                                            <p class="font-headline font-bold text-headline-sm text-primary">E-Raport Digital</p>
                                            <p class="font-body text-body-sm text-on-surface-variant">Pantau perkembangan siswa</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 rounded-2xl border border-outline-variant/40 bg-surface p-4">
                                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary/10 text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[22px]">groups</span>
                                        </span>
                                        <div>
                                            <p class="font-headline font-bold text-headline-sm text-primary">Semua Program</p>
                                            <p class="font-body text-body-sm text-on-surface-variant">Private hingga reguler</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-7 lg:order-1">
                                <span class="font-bold tracking-wider uppercase text-orange font-headline text-label-md">Penjelasan Khusus</span>
                                <h2 class="mt-2 mb-5 font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">{{ $settings['renang_khusus_heading'] }}</h2>
                                <div class="space-y-4 text-on-surface-variant font-body text-body-md md:text-body-lg leading-relaxed">
                                    @forelse ($paragraphsKhusus as $par)
                                        <p>{{ $par }}</p>
                                    @empty
                                        <p>{{ $settings['renang_khusus_text'] ?? '' }}</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="scroll-mt-24 bg-surface py-16 md:py-24" id="faq">
                <div class="mx-auto max-w-3xl px-margin_mobile md:px-margin_desktop">
                    <div class="mb-10 text-center md:mb-14">
                        <span class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full font-body text-label-md font-semibold mb-4">
                            <span class="material-symbols-outlined text-[18px]">help</span>
                            FAQ
                        </span>
                        <h2 class="font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">{{ $renangFaqHeading }}</h2>
                        @if ($renangFaqSubtitle)
                            <p class="mt-4 max-w-2xl mx-auto text-on-surface-variant font-body text-body-lg">{{ $renangFaqSubtitle }}</p>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse ($faqs as $i => $faq)
                            <div class="overflow-hidden border border-outline-variant/40 rounded-2xl bg-surface-container-lowest pool-shadow" x-data="{ expanded: false }">
                                <button @click="expanded = !expanded" :aria-expanded="expanded ? 'true' : 'false'"
                                    class="flex items-center justify-between w-full gap-4 p-5 text-left transition-colors md:p-6 group hover:bg-surface-container-low">
                                    <span class="flex items-center min-w-0 gap-4">
                                        <span class="flex items-center justify-center w-9 h-9 font-bold rounded-full shrink-0 bg-primary/10 text-primary font-headline text-label-md transition-colors group-hover:bg-primary group-hover:text-on-primary">
                                            {{ $i + 1 }}
                                        </span>
                                        <span class="font-bold leading-snug font-headline text-headline-sm md:text-headline-md text-primary">{{ $faq->question }}</span>
                                    </span>
                                    <span class="flex items-center justify-center w-9 h-9 transition-transform duration-300 border rounded-full shrink-0 border-outline-variant/50 text-on-surface-variant group-hover:bg-primary/10"
                                          :class="expanded ? 'rotate-180 bg-primary text-on-primary border-primary' : ''">
                                        <span class="material-symbols-outlined text-[20px]">expand_more</span>
                                    </span>
                                </button>
                                <div class="overflow-hidden transition-[max-height] duration-300 ease-in-out"
                                     x-bind:style="expanded ? 'max-height: ' + $refs.body.scrollHeight + 'px' : 'max-height: 0px'">
                                    <div x-ref="body" class="px-5 pb-6 md:px-6 md:pb-7">
                                        <div class="pt-5 border-t border-outline-variant/30">
                                            <div class="pl-4 leading-relaxed border-l-4 border-orange md:pl-5 text-on-surface-variant text-body-md md:text-body-lg">
                                                <p>{{ $faq->answer }}</p>
                                                @if ($faq->videos->isNotEmpty())
                                                    <div class="mt-6 space-y-6">
                                                        @foreach ($faq->videos as $video)
                                                            @if ($video->embed_url)
                                                                <div>
                                                                    @if ($video->title)
                                                                        <p class="mb-2 font-headline font-bold text-headline-sm text-primary">{{ $video->title }}</p>
                                                                    @endif
                                                                    <div class="overflow-hidden border border-outline-variant/30 rounded-2xl aspect-video pool-shadow">
                                                                        <iframe class="w-full h-full" src="{{ $video->embed_url }}" title="{{ $video->title ?: $faq->question }}"
                                                                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                            allowfullscreen loading="lazy"></iframe>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center rounded-2xl border border-outline-variant/40 bg-surface-container-lowest pool-shadow text-on-surface-variant">
                                <span class="material-symbols-outlined text-[40px] text-on-surface-variant/50">quiz</span>
                                <p class="mt-3">Belum ada pertanyaan. Silakan tambahkan melalui pengaturan admin.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="bg-surface-container-low py-16 md:py-24">
                <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
                    <div class="relative p-8 overflow-hidden text-center shadow-xl bg-primary rounded-3xl md:p-12 text-on-primary shadow-primary/20">
                        <div class="absolute w-64 h-64 rounded-full -top-16 -left-16 bg-orange/20 blur-3xl"></div>
                        <div class="absolute rounded-full -bottom-20 -right-10 w-72 h-72 bg-surface/10 blur-3xl"></div>
                        <div class="relative z-10">
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface/15 backdrop-blur-sm font-body text-label-md font-semibold mb-5">
                                <span class="material-symbols-outlined text-[18px]">waves</span>
                                Gabung Sekarang
                            </span>
                            <h2 class="mb-3 font-bold font-headline text-headline-lg-mobile md:text-headline-xl">Siap Belajar Renang Bersama ASC?</h2>
                            <p class="max-w-xl mx-auto mb-8 font-body text-body-md md:text-body-lg text-on-primary/90">Daftarkan diri Anda atau buah hati Anda dan rasakan pengalaman belajar renang yang aman, menyenangkan, dan terstruktur.</p>
                            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center gap-2 px-8 py-4 text-white transition-colors shadow-lg bg-orange rounded-xl font-body text-label-md hover:bg-orange-light shadow-orange/40 active:scale-95">
                                    <span class="material-symbols-outlined text-[18px]">edit_note</span>
                                    Daftar Sekarang
                                </a>
                                <a href="{{ url('/program') }}"
                                    class="inline-flex items-center justify-center gap-2 px-8 py-4 transition-colors border-2 bg-surface/10 backdrop-blur-sm border-on-primary text-on-primary rounded-xl font-body text-label-md hover:bg-surface/20 active:scale-95">
                                    <span class="material-symbols-outlined text-[18px]">pool</span>
                                    Lihat Program
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>