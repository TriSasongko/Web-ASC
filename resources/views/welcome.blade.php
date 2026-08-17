<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Antasena Swimming Club - Belajar Renang Bersama Coach Berpengalaman</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_ASR.png') }}">

    <!-- Fonts: Manrope & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

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

        html {
            scroll-behavior: smooth;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body
    class="antialiased bg-background text-on-background font-body text-body-md selection:bg-primary-container selection:text-on-primary">

    <!-- 1. Navbar -->
    @include('partials.header')

    @php $s = $settings; @endphp

    <!-- 2. Hero Section -->
    <section
        class="relative pt-28 pb-16 md:pt-32 md:pb-24 min-h-[80vh] md:min-h-[90vh] flex items-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <div class="w-full h-full bg-center bg-cover" style="background-image: url('{{ $s['hero_image'] ?? '' }}')">
            </div>
            <div class="absolute inset-0 bg-primary/80 mix-blend-multiply backdrop-blur-[2px]"></div>
        </div>

        <div
            class="relative z-10 grid items-center w-full grid-cols-1 gap-12 mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop lg:grid-cols-2">
            <!-- Text Content -->
            <div class="space-y-6 text-on-primary">
                <h1 class="leading-tight font-headline text-headline-lg-mobile md:text-headline-xl text-on-primary">
                    {{ $s['hero_title'] ?? 'Belajar Renang Bersama ' }}<span
                        class="text-orange-lighter">{{ $s['hero_highlight'] ?? 'Coach Berpengalaman' }}</span>
                </h1>
                <p class="max-w-xl font-body text-body-lg text-on-primary/90">
                    {{ $s['hero_subtitle'] ?? 'Program latihan aman, menyenangkan, dan berorientasi pada pencapaian, didampingi secara khusus oleh coach ahli bersertifikat.' }}
                </p>
                <div class="flex flex-col gap-4 pt-4 sm:flex-row">
                    <a href="{{ route('register') }}"
                        class="px-8 py-4 text-center text-white transition-colors rounded-lg shadow-lg bg-orange font-body text-label-md hover:bg-orange-light shadow-orange/30">
                        {{ $s['hero_cta_primary'] ?? 'Daftar Sekarang' }}
                    </a>
                    <a href="{{ url('/program') }}"
                        class="px-8 py-4 text-center transition-colors border-2 rounded-lg bg-surface/10 backdrop-blur-sm border-on-primary text-on-primary font-body text-label-md hover:bg-surface/20">
                        {{ $s['hero_cta_secondary'] ?? 'Lihat Program' }}
                    </a>
                </div>
            </div>

            <!-- Illustration/Photo -->
            <div class="relative hidden lg:block">
                <div class="absolute inset-0 bg-orange/20 rounded-[2rem] transform rotate-3 scale-105 z-0 blur-xl">
                </div>
                <img class="relative z-10 w-full h-auto rounded-[2rem] shadow-2xl object-cover aspect-[4/3] border-4 border-surface/30"
                    alt="{{ $s['hero_side_image_alt'] ?? 'Anak-anak belajar renang bersama coach ASC' }}"
                    src="{{ $s['hero_side_image'] ?? '' }}">
            </div>
        </div>
    </section>

    <!-- 3. Tentang ASC -->
    <section class="py-16 md:py-24 bg-surface" id="tentang">
        <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
            <!-- Statistik -->
            <div class="grid grid-cols-2 gap-3 mb-10 lg:grid-cols-4 md:gap-6 md:mb-16">
                <div class="p-4 text-center shadow-lg bg-primary text-on-primary rounded-2xl md:p-6 shadow-primary/20">
                    <p class="font-bold font-headline text-headline-md md:text-headline-lg">
                        {{ $s['tentang_years'] ?? '10+' }}</p>
                    <p class="mt-1 tracking-wider uppercase font-body text-label-sm text-on-primary/90">
                        {{ $s['tentang_years_label'] ?? 'Tahun Pengalaman' }}</p>
                </div>
                <div
                    class="p-4 text-center border bg-surface-container-low rounded-2xl md:p-6 border-outline-variant/30">
                    <p class="font-bold font-headline text-headline-md md:text-headline-lg text-primary">
                        {{ $programs->count() }}</p>
                    <p class="mt-1 tracking-wider uppercase font-body text-label-sm text-on-surface-variant">Program
                        Kelas</p>
                </div>
                <div
                    class="p-4 text-center border bg-surface-container-low rounded-2xl md:p-6 border-outline-variant/30">
                    <p class="font-bold font-headline text-headline-md md:text-headline-lg text-primary">
                        {{ $coaches->count() }}</p>
                    <p class="mt-1 tracking-wider uppercase font-body text-label-sm text-on-surface-variant">Coach
                        Profesional</p>
                </div>
                <div
                    class="p-4 text-center border bg-surface-container-low rounded-2xl md:p-6 border-outline-variant/30">
                    <p class="font-bold font-headline text-headline-md md:text-headline-lg text-primary">
                        {{ $gallery->count() }}</p>
                    <p class="mt-1 tracking-wider uppercase font-body text-label-sm text-on-surface-variant">Foto Galeri
                    </p>
                </div>
            </div>

            <div class="grid items-center grid-cols-1 gap-8 md:grid-cols-2 md:gap-12">
                <div class="space-y-5 md:space-y-6">
                    <h2 class="font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">
                        {{ $s['tentang_heading'] ?? 'Tentang Antasena Swimming Club' }}</h2>
                    <p class="text-on-surface-variant font-body text-body-md md:text-body-lg">
                        {{ $s['tentang_text'] ?? 'Berdiri sejak tahun 2010, Antasena Swimming Club (ASC) telah mendedikasikan diri untuk mencetak generasi perenang yang tangguh, percaya diri, dan berprestasi. Kami percaya bahwa berenang bukan sekadar olahraga, melainkan keterampilan hidup (life skill) yang esensial.' }}
                    </p>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="p-5 border-t-4 bg-surface-container-low rounded-xl border-orange">
                            <div class="flex items-center justify-center w-10 h-10 mb-3 rounded-lg bg-orange/15">
                                <span class="material-symbols-outlined text-orange">visibility</span>
                            </div>
                            <h3 class="mb-1 font-semibold font-headline text-headline-sm text-primary">Visi</h3>
                            <p class="text-on-surface-variant text-body-sm md:text-body-md">
                                {{ $s['tentang_visi'] ?? 'Menjadi klub renang terbaik yang menginspirasi gaya hidup sehat dan mencetak atlet berprestasi di tingkat nasional maupun internasional.' }}
                            </p>
                        </div>
                        <div class="p-5 border-t-4 bg-surface-container-low rounded-xl border-primary">
                            <div class="flex items-center justify-center w-10 h-10 mb-3 rounded-lg bg-primary/15">
                                <span class="material-symbols-outlined text-primary">flag</span>
                            </div>
                            <h3 class="mb-1 font-semibold font-headline text-headline-sm text-primary">Misi</h3>
                            <ul
                                class="list-disc list-inside text-on-surface-variant text-body-sm md:text-body-md mt-2 space-y-1.5">
                                @foreach (collect(preg_split('/\r\n|\r|\n/', $s['tentang_misi'] ?? ''))->map(fn($line) => trim($line))->filter() as $misi)
                                    <li>{{ $misi }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img alt="Kegiatan latihan renang di ASC"
                        class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3]"
                        src="{{ $s['tentang_image'] ?? '' }}">
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Keunggulan ASC -->
    <section class="py-16 md:py-24 bg-surface-container-lowest">
        <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
            <div class="mb-8 text-center md:mb-12">
                <h2 class="font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">Mengapa
                    Memilih ASC?</h2>
                <p class="max-w-2xl mx-auto mt-4 text-on-surface-variant font-body text-body-lg">Kami memberikan yang
                    terbaik untuk perkembangan dan kenyamanan proses belajar renang Anda.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 md:gap-6">
                <!-- Card 1 -->
                <div
                    class="flex items-center gap-4 p-5 transition-shadow border shadow-sm bg-surface sm:p-6 rounded-xl border-outline-variant/30 hover:shadow-md sm:flex-col sm:items-center sm:text-center group">
                    <div
                        class="flex items-center justify-center transition-colors rounded-full w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 shrink-0 group-hover:bg-primary/20">
                        <span class="text-3xl material-symbols-outlined text-primary">sports</span>
                    </div>
                    <div class="min-w-0 sm:text-center">
                        <h3 class="mb-1 font-semibold font-headline text-headline-sm text-primary sm:mb-2">Coach
                            Berpengalaman</h3>
                        <p class="text-on-surface-variant text-body-md">Didampingi oleh pelatih profesional dan
                            bersertifikat yang ahli di bidangnya.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div
                    class="flex items-center gap-4 p-5 transition-shadow border shadow-sm bg-surface sm:p-6 rounded-xl border-outline-variant/30 hover:shadow-md sm:flex-col sm:items-center sm:text-center group">
                    <div
                        class="flex items-center justify-center transition-colors rounded-full w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 shrink-0 group-hover:bg-primary/20">
                        <span class="text-3xl material-symbols-outlined text-primary">mood</span>
                    </div>
                    <div class="min-w-0 sm:text-center">
                        <h3 class="mb-1 font-semibold font-headline text-headline-sm text-primary sm:mb-2">Metode
                            Belajar Menyenangkan</h3>
                        <p class="text-on-surface-variant text-body-md">Pendekatan yang ramah dan interaktif membuat
                            proses belajar renang jadi lebih seru.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div
                    class="flex items-center gap-4 p-5 transition-shadow border shadow-sm bg-surface sm:p-6 rounded-xl border-outline-variant/30 hover:shadow-md sm:flex-col sm:items-center sm:text-center group">
                    <div
                        class="flex items-center justify-center transition-colors rounded-full w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 shrink-0 group-hover:bg-primary/20">
                        <span class="text-3xl material-symbols-outlined text-primary">pool</span>
                    </div>
                    <div class="min-w-0 sm:text-center">
                        <h3 class="mb-1 font-semibold font-headline text-headline-sm text-primary sm:mb-2">Fasilitas
                            Lengkap</h3>
                        <p class="text-on-surface-variant text-body-md">Kolam renang berstandar dengan fasilitas
                            pendukung yang memadai untuk berlatih.</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div
                    class="flex items-center gap-4 p-5 transition-shadow border shadow-sm bg-surface sm:p-6 rounded-xl border-outline-variant/30 hover:shadow-md sm:flex-col sm:items-center sm:text-center group">
                    <div
                        class="flex items-center justify-center transition-colors rounded-full w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 shrink-0 group-hover:bg-primary/20">
                        <span class="text-3xl material-symbols-outlined text-primary">health_and_safety</span>
                    </div>
                    <div class="min-w-0 sm:text-center">
                        <h3 class="mb-1 font-semibold font-headline text-headline-sm text-primary sm:mb-2">Aman dan
                            Nyaman</h3>
                        <p class="text-on-surface-variant text-body-md">Prioritas utama pada keselamatan siswa dengan
                            pengawasan ketat selama latihan.</p>
                    </div>
                </div>
                <!-- Card 5 -->
                <div
                    class="flex items-center gap-4 p-5 transition-shadow border shadow-sm bg-surface sm:p-6 rounded-xl border-outline-variant/30 hover:shadow-md sm:flex-col sm:items-center sm:text-center group">
                    <div
                        class="flex items-center justify-center transition-colors rounded-full w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 shrink-0 group-hover:bg-primary/20">
                        <span class="text-3xl material-symbols-outlined text-primary">calendar_month</span>
                    </div>
                    <div class="min-w-0 sm:text-center">
                        <h3 class="mb-1 font-semibold font-headline text-headline-sm text-primary sm:mb-2">Jadwal
                            Fleksibel</h3>
                        <p class="text-on-surface-variant text-body-md">Pilihan waktu latihan yang dapat disesuaikan
                            dengan kesibukan Anda.</p>
                    </div>
                </div>
                <!-- Card 6 -->
                <div
                    class="flex items-center gap-4 p-5 transition-shadow border shadow-sm bg-surface sm:p-6 rounded-xl border-outline-variant/30 hover:shadow-md sm:flex-col sm:items-center sm:text-center group">
                    <div
                        class="flex items-center justify-center transition-colors rounded-full w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 shrink-0 group-hover:bg-primary/20">
                        <span class="text-3xl material-symbols-outlined text-primary">analytics</span>
                    </div>
                    <div class="min-w-0 sm:text-center">
                        <h3 class="mb-1 font-semibold font-headline text-headline-sm text-primary sm:mb-2">E-Raport
                        </h3>
                        <p class="text-on-surface-variant text-body-md">Pantau perkembangan kemampuan renang secara
                            transparan melalui laporan digital berkala.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Program Kelas -->
    <section class="py-16 overflow-hidden md:py-24 bg-surface" id="program">
        <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
            <div class="mb-10 text-center md:mb-14">
                <span class="px-3.5 py-1.5 rounded-full bg-primary/10 text-primary font-headline text-label-md font-bold uppercase tracking-wider">Pilihan Terbaik</span>
                <h2 class="mt-2 font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">
                    {{ $s['program_heading'] ?? 'Program Kelas Kami' }}
                </h2>
                <p class="max-w-2xl mx-auto mt-3 text-on-surface-variant font-body text-body-lg">
                    {{ $s['program_subtitle'] ?? 'Pilih program yang paling sesuai dengan kebutuhan dan target Anda.' }}
                </p>
            </div>

            @if ($programs->isNotEmpty())
                <div class="relative group/prog" x-data="{
                    active: 0,
                    total: {{ $programs->count() }},
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
                        this.timer = setInterval(() => this.next(), 4500);
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

                    <!-- Slider Container -->
                    <div x-ref="slider" @scroll.debounce.50ms="updateActiveOnScroll()"
                        class="flex gap-4 md:gap-8 overflow-x-auto snap-x snap-mandatory py-12 px-[15vw] md:px-[35vw] no-scrollbar scroll-smooth items-center">
                        @foreach ($programs as $index => $program)
                            <div class="shrink-0 w-[280px] sm:w-[340px] md:w-[380px] snap-center transition-all duration-500 transform"
                                 :class="active === {{ $index }} ? 'scale-105 z-20 opacity-100' : 'scale-90 opacity-50 z-10 blur-[0.5px]'">

                                <div class="relative flex flex-col h-full p-6 overflow-hidden transition-all duration-500 border md:p-8 bg-surface-container-lowest rounded-3xl"
                                     :class="active === {{ $index }} ? 'shadow-2xl border-orange/40 ring-2 ring-orange/20' : 'shadow-md border-outline-variant/30'">

                                    <!-- Badge Top -->
                                    <div class="flex items-start justify-between gap-2 mb-4">
                                        <h3 class="font-bold font-headline text-headline-sm md:text-headline-md text-primary">
                                            {{ $program->name }}
                                        </h3>
                                        @if ($program->badge)
                                            <span class="shrink-0 bg-gradient-to-r from-orange to-orange-light text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-md uppercase tracking-wide">
                                                {{ $program->badge }}
                                            </span>
                                        @endif
                                    </div>

                                    @if ($program->subtitle)
                                        <p class="mb-5 text-on-surface-variant text-body-sm line-clamp-2">
                                            {{ $program->subtitle }}
                                        </p>
                                    @endif

                                    <!-- Pricing Card -->
                                    <div class="p-4 mb-6 border rounded-2xl bg-surface-container-low border-outline-variant/20">
                                        @if ($program->price)
                                            <div class="flex items-baseline gap-1">
                                                <span class="font-extrabold font-headline text-headline-lg text-orange">Rp{{ number_format($program->price, 0, ',', '.') }}</span>
                                                <span class="font-medium text-on-surface-variant text-body-sm">{{ $program->billing_unit }}</span>
                                            </div>
                                        @else
                                            <span class="font-bold font-headline text-headline-md text-orange">Hubungi Kami</span>
                                        @endif
                                    </div>

                                    <!-- Features List -->
                                    <ul class="flex-grow mb-8 space-y-3.5">
                                        @foreach ($program->featureList() as $feature)
                                            <li class="flex items-start gap-3 text-on-surface-variant text-body-sm md:text-body-md">
                                                <span class="mt-0.5 text-base material-symbols-outlined text-orange shrink-0">check_circle</span>
                                                <span class="leading-relaxed">{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <!-- CTA Button -->
                                    <a href="{{ route('register') }}"
                                       class="w-full text-center py-3.5 rounded-xl font-body text-label-md font-semibold transition-all duration-300 shadow-md"
                                       :class="active === {{ $index }} ? 'bg-orange text-white hover:bg-orange-light shadow-orange/30 scale-102' : 'bg-primary text-on-primary hover:bg-primary-container'">
                                        {{ $program->button_label ?? 'Pilih Program' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Controls -->
                    <button type="button" @click="prev()" aria-label="Program sebelumnya"
                        class="absolute z-30 flex items-center justify-center w-12 h-12 transition-all duration-300 -translate-y-1/2 border rounded-full shadow-2xl left-4 md:left-12 top-1/2 bg-surface/90 text-primary backdrop-blur-md border-outline-variant/40 hover:bg-primary hover:text-white hover:scale-110">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button type="button" @click="next()" aria-label="Program berikutnya"
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
                <p class="text-center text-on-surface-variant">Belum ada program.</p>
            @endif
        </div>
    </section>

    <!-- 6. Jadwal Latihan -->
    <section class="py-16 md:py-24 bg-surface-container-lowest" id="jadwal">
        <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
            <div class="mb-8 text-center md:mb-12">
                <h2 class="font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">
                    {{ $s['jadwal_heading'] ?? 'Jadwal Latihan Reguler' }}</h2>
                <p class="max-w-2xl mx-auto mt-4 text-on-surface-variant font-body text-body-lg">
                    {{ $s['jadwal_subtitle'] ?? 'Untuk jadwal Private dan Mini Private dapat didiskusikan langsung dengan Coach.' }}
                </p>
            </div>
            <!-- Mobile: kartu jadwal -->
            <div class="grid grid-cols-1 gap-3 md:hidden">
                @forelse ($jadwalRows as $row)
                    <div
                        class="flex items-start gap-4 p-4 border shadow-sm bg-surface rounded-xl border-outline-variant/30">
                        <div class="flex items-center justify-center rounded-lg w-11 h-11 bg-primary/10 shrink-0">
                            <span class="text-2xl material-symbols-outlined text-primary">calendar_month</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold font-headline text-headline-sm text-primary">{{ $row['day'] }}
                            </h3>
                            <p class="text-on-surface-variant text-body-sm mt-0.5">{{ $row['program'] }}</p>
                            <p class="flex items-center gap-1 text-body-sm mt-1.5">
                                <span class="text-base material-symbols-outlined text-orange">schedule</span>
                                <span>{{ $row['time'] }}</span>
                            </p>
                            <p class="flex items-start gap-1 mt-1 text-body-sm text-on-surface-variant">
                                <span class="text-base material-symbols-outlined text-orange">location_on</span>
                                <span>{{ $row['location'] }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-on-surface-variant">Jadwal belum diatur.</p>
                @endforelse
            </div>

            <!-- Desktop: tabel -->
            <div class="hidden overflow-x-auto border shadow-sm md:block rounded-xl border-outline-variant/30">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-primary text-on-primary font-body text-label-md">
                            <th class="p-4 border-b border-outline-variant/30">Hari</th>
                            <th class="p-4 border-b border-outline-variant/30">Jam</th>
                            <th class="p-4 border-b border-outline-variant/30">Program</th>
                            <th class="p-4 border-b border-outline-variant/30">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="text-on-surface-variant">
                        @forelse ($jadwalRows as $row)
                            <tr
                                class="hover:bg-surface-container-low transition-colors {{ $loop->last ? '' : 'border-b border-outline-variant/20' }}">
                                <td class="p-4 font-semibold text-primary">{{ $row['day'] }}</td>
                                <td class="p-4">{{ $row['time'] }}</td>
                                <td class="p-4">{{ $row['program'] }}</td>
                                <td class="p-4">{{ $row['location'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center">Jadwal belum diatur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- 7. Coach Kami (Re-designed with Center Focus) -->
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

    <!-- 8. Galeri -->
    <section class="py-20 overflow-hidden md:py-28 bg-surface-container-lowest" id="galeri">

        <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">

            <!-- Header -->
            <div class="flex flex-col gap-6 mb-10 md:flex-row md:items-end md:justify-between">

                <div>

                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 mb-4 text-sm font-semibold rounded-full bg-orange/10 text-orange">

                        <span class="text-lg material-symbols-outlined">
                            photo_library
                        </span>

                        Galeri ASC

                    </span>

                    <h2 class="font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">

                        Galeri Kegiatan

                    </h2>

                    <p class="max-w-2xl mt-4 text-on-surface-variant font-body text-body-lg">

                        Lihat berbagai momen latihan,
                        kebersamaan, dan pencapaian siswa
                        Antasena Swimming Club.

                    </p>

                </div>

                <a href="{{ url('/galeri') }}"
                    class="inline-flex items-center gap-2 font-semibold transition-colors text-primary hover:text-orange">

                    Lihat Semua

                    <span class="material-symbols-outlined">
                        arrow_forward
                    </span>

                </a>

            </div>


            @if ($gallery->isNotEmpty())

                <!-- Gallery Carousel -->

                <div x-data="{
                    current: 0,
                    total: {{ $gallery->count() }},
                    timer: null,
                    startX: 0,
                    endX: 0,

                    next() {
                        this.current =
                            this.current >= this.total - 1 ?
                            0 :
                            this.current + 1;
                    },

                    prev() {
                        this.current =
                            this.current <= 0 ?
                            this.total - 1 :
                            this.current - 1;
                    },

                    goTo(index) {
                        this.current = index;
                    },

                    startAutoPlay() {
                        this.stopAutoPlay();

                        this.timer = setInterval(() => {
                            this.next();
                        }, 5000);
                    },

                    stopAutoPlay() {
                        if (this.timer) {
                            clearInterval(this.timer);
                            this.timer = null;
                        }
                    },

                    touchStart(e) {
                        this.startX = e.changedTouches[0].screenX;
                    },

                    touchEnd(e) {
                        this.endX = e.changedTouches[0].screenX;

                        if (this.startX - this.endX > 50) {
                            this.next();
                        }

                        if (this.endX - this.startX > 50) {
                            this.prev();
                        }

                        this.startAutoPlay();
                    },

                    init() {
                        this.startAutoPlay();
                    }
                }" x-init="init()" @mouseenter="stopAutoPlay()"
                    @mouseleave="startAutoPlay()" @touchstart="touchStart($event)" @touchend="touchEnd($event)"
                    class="relative">

                    <!-- Slides -->

                    <div class="overflow-hidden rounded-3xl">

                        <div class="flex transition-transform
                               duration-700
                               ease-[cubic-bezier(.22,1,.36,1)]"
                            :style="`transform: translateX(-${current * 100}%)`">

                            @foreach ($gallery as $image)
                                <div class="w-full shrink-0">

                                    <a href="{{ url('/galeri') }}"
                                        class="relative block
                                           aspect-[16/8]
                                           md:aspect-[16/7]
                                           overflow-hidden
                                           group">

                                        <img src="{{ $image->url }}" alt="{{ $image->title ?? 'Galeri ASC' }}"
                                            loading="lazy"
                                            class="absolute inset-0 object-cover w-full h-full transition-transform duration-1000 group-hover:scale-105">

                                        <!-- Overlay -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                                        </div>

                                        <!-- Zoom icon -->
                                        <div class="absolute inset-0 flex items-center justify-center">

                                            <div
                                                class="flex items-center justify-center w-16 h-16 transition-all duration-500 scale-75 border rounded-full opacity-0 bg-white/20 backdrop-blur-md border-white/30 group-hover:opacity-100 group-hover:scale-100">

                                                <span class="text-3xl text-white material-symbols-outlined">
                                                    zoom_in
                                                </span>

                                            </div>

                                        </div>

                                        <!-- Category -->
                                        @if ($image->category)
                                            <span
                                                class="absolute px-4 py-2 text-xs font-bold text-white rounded-full shadow-lg top-5 left-5 bg-orange">
                                                {{ $image->category }}
                                            </span>
                                        @endif

                                        <!-- Content -->
                                        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10">

                                            @if ($image->title)
                                                <h3 class="text-xl font-bold text-white font-headline md:text-3xl">
                                                    {{ $image->title }}
                                                </h3>
                                            @endif

                                            @if ($image->description)
                                                <p
                                                    class="max-w-2xl mt-2 text-sm text-white/80 md:text-base line-clamp-2">
                                                    {{ $image->description }}
                                                </p>
                                            @endif

                                        </div>

                                    </a>

                                </div>
                            @endforeach

                        </div>

                    </div>


                    <!-- Previous -->

                    <button type="button" @click="prev()" aria-label="Galeri sebelumnya"
                        class="absolute z-10 flex items-center justify-center transition-all duration-300 -translate-y-1/2 rounded-full shadow-xl left-3 md:left-5 top-1/2 w-11 h-11 md:w-14 md:h-14 bg-white/90 backdrop-blur-md text-primary hover:bg-primary hover:text-white hover:scale-110">

                        <span class="material-symbols-outlined">
                            chevron_left
                        </span>

                    </button>


                    <!-- Next -->

                    <button type="button" @click="next()" aria-label="Galeri berikutnya"
                        class="absolute z-10 flex items-center justify-center transition-all duration-300 -translate-y-1/2 rounded-full shadow-xl right-3 md:right-5 top-1/2 w-11 h-11 md:w-14 md:h-14 bg-white/90 backdrop-blur-md text-primary hover:bg-primary hover:text-white hover:scale-110">

                        <span class="material-symbols-outlined">
                            chevron_right
                        </span>

                    </button>

                </div>


                <!-- Thumbnail Navigation -->

                <div class="mt-5">

                    <div
                        class="flex gap-3 overflow-x-auto
                           pb-2
                           [scrollbar-width:none]
                           [&::-webkit-scrollbar]:hidden">

                        @foreach ($gallery as $image)
                            <button type="button" @click="goTo({{ $loop->index }})"
                                class="relative w-24 h-16 overflow-hidden transition-all duration-300 border-2 shrink-0 md:w-32 md:h-20 rounded-xl"
                                :class="current === {{ $loop->index }} ?
                                    'border-orange scale-105 shadow-lg' :
                                    'border-transparent opacity-60 hover:opacity-100'">

                                <img src="{{ $image->url }}" alt="{{ $image->title ?? 'Thumbnail galeri' }}"
                                    class="object-cover w-full h-full">

                                <div class="absolute inset-0 bg-black/20"
                                    :class="current === {{ $loop->index }} ?
                                        'bg-transparent' :
                                        'bg-black/30'">
                                </div>

                            </button>
                        @endforeach

                    </div>

                </div>


                <!-- Dots -->

                <div class="flex justify-center gap-2 mt-5">

                    @foreach ($gallery as $image)
                        <button type="button" @click="goTo({{ $loop->index }})"
                            class="h-2.5 rounded-full
                               transition-all duration-300"
                            :class="current === {{ $loop->index }} ?
                                'w-8 bg-orange' :
                                'w-2.5 bg-primary/20 hover:bg-primary/40'"
                            aria-label="Galeri {{ $loop->iteration }}"></button>
                    @endforeach

                </div>
            @else
                <div class="py-16 text-center">

                    <span class="text-6xl material-symbols-outlined text-on-surface-variant">
                        photo_library
                    </span>

                    <p class="mt-4 text-on-surface-variant">
                        Belum ada foto galeri.
                    </p>

                </div>

            @endif

        </div>

    </section>

    <!-- 9. FAQ -->
    <section class="py-16 md:py-24 bg-surface-container-lowest" id="faq">
        <div class="max-w-3xl mx-auto px-margin_mobile md:px-margin_desktop">
            <div class="mb-8 text-center md:mb-12">
                <h2 class="font-bold font-headline text-headline-lg-mobile md:text-headline-lg text-primary">Pertanyaan
                    Umum (FAQ)</h2>
                <p class="mt-4 text-on-surface-variant font-body text-body-lg">Jawaban atas beberapa pertanyaan yang
                    sering diajukan.</p>
            </div>
            <div class="space-y-4">
                <!-- Item 1 -->
                <div class="overflow-hidden border border-outline-variant/50 rounded-xl" x-data="{ expanded: false }">
                    <button @click="expanded = !expanded"
                        class="flex items-center justify-between w-full p-4 text-left transition-colors bg-surface font-headline text-headline-sm text-primary hover:bg-surface-container-low">
                        <span>Bagaimana cara mendaftar di ASC?</span>
                        <span class="transition-transform duration-300 material-symbols-outlined"
                            x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div class="p-4 border-t bg-surface border-outline-variant/30 text-on-surface-variant" x-cloak
                        x-show="expanded">
                        Pendaftaran dapat dilakukan secara online dengan mengklik tombol "Daftar" di website ini, atau
                        Anda bisa datang langsung ke meja pendaftaran kami di Kolam Renang Universitas Lampung pada jam
                        operasional.
                    </div>
                </div>
                <!-- Item 2 -->
                <div class="overflow-hidden border border-outline-variant/50 rounded-xl" x-data="{ expanded: false }">
                    <button @click="expanded = !expanded"
                        class="flex items-center justify-between w-full p-4 text-left transition-colors bg-surface font-headline text-headline-sm text-primary hover:bg-surface-container-low">
                        <span>Mulai usia berapa anak bisa ikut kelas renang?</span>
                        <span class="transition-transform duration-300 material-symbols-outlined"
                            x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div class="p-4 border-t bg-surface border-outline-variant/30 text-on-surface-variant" x-cloak
                        x-show="expanded">
                        Kami menerima siswa mulai dari usia 4 tahun untuk program reguler/mini reguler anak-anak. Untuk
                        usia di bawah 4 tahun, disarankan mengikuti program private dengan pendampingan khusus.
                    </div>
                </div>
                <!-- Item 3 -->
                <div class="overflow-hidden border border-outline-variant/50 rounded-xl" x-data="{ expanded: false }">
                    <button @click="expanded = !expanded"
                        class="flex items-center justify-between w-full p-4 text-left transition-colors bg-surface font-headline text-headline-sm text-primary hover:bg-surface-container-low">
                        <span>Bagaimana sistem pembayaran biayanya?</span>
                        <span class="transition-transform duration-300 material-symbols-outlined"
                            x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div class="p-4 border-t bg-surface border-outline-variant/30 text-on-surface-variant" x-cloak
                        x-show="expanded">
                        Pembayaran dapat dilakukan melalui transfer bank, e-wallet (GoPay, OVO, Dana), atau secara tunai
                        di lokasi pendaftaran. Pembayaran dilakukan di awal sebelum sesi pertama dimulai.
                    </div>
                </div>
                <!-- Item 4 -->
                <div class="overflow-hidden border border-outline-variant/50 rounded-xl" x-data="{ expanded: false }">
                    <button @click="expanded = !expanded"
                        class="flex items-center justify-between w-full p-4 text-left transition-colors bg-surface font-headline text-headline-sm text-primary hover:bg-surface-container-low">
                        <span>Apakah biaya sudah termasuk tiket masuk kolam?</span>
                        <span class="transition-transform duration-300 material-symbols-outlined"
                            x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div class="p-4 border-t bg-surface border-outline-variant/30 text-on-surface-variant" x-cloak
                        x-show="expanded">
                        Ya, seluruh biaya program kelas (Private, Reguler, dll) yang tercantum sudah termasuk biaya
                        tiket masuk kolam renang untuk siswa selama sesi latihan berlangsung. Pendamping/orang tua yang
                        masuk area kolam namun tidak berenang mungkin dikenakan tarif masuk reguler kolam renang (bukan
                        dari pihak ASC).
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 11. Call to Action -->
    <section class="py-16 md:py-24 bg-surface">
        <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
            <div
                class="relative p-10 overflow-hidden text-center shadow-2xl bg-primary rounded-3xl md:p-16 text-on-primary">
                <!-- Decorative elements -->
                <div
                    class="absolute top-0 right-0 w-64 h-64 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-primary-container mix-blend-multiply filter blur-3xl opacity-70">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-64 h-64 transform -translate-x-1/2 translate-y-1/2 rounded-full opacity-50 bg-orange/30 mix-blend-overlay filter blur-3xl">
                </div>
                <div class="relative z-10">
                    <h2 class="mb-4 font-bold font-headline text-headline-lg-mobile md:text-headline-xl">Siap Memulai
                        Perjalanan Renang Anda?</h2>
                    <p class="max-w-2xl mx-auto mb-8 font-body text-body-lg text-on-primary/90">Bergabunglah dengan
                        ratusan siswa lainnya yang telah merasakan manfaat belajar renang bersama Antasena Swimming
                        Club. Daftar sekarang dan jadilah perenang tangguh!</p>
                    <a href="{{ route('register') }}"
                        class="inline-block px-8 py-4 text-white transition-colors duration-200 transform shadow-lg bg-orange rounded-xl font-body text-label-md hover:bg-orange-light shadow-orange/40 hover:scale-105">
                        Daftar Sekarang Juga
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. Footer -->
    @include('partials.footer')
</body>

</html>
