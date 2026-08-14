<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Antasena Swimming Club - Belajar Renang Bersama Coach Berpengalaman</title>

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
            html {
                scroll-behavior: smooth;
            }
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="bg-background text-on-background font-body text-body-md antialiased selection:bg-primary-container selection:text-on-primary">

        <!-- 1. Navbar -->
        @include('partials.header')

        @php $s = $settings; @endphp

        <!-- 2. Hero Section -->
        <section class="relative pt-28 pb-16 md:pt-32 md:pb-24 min-h-[80vh] md:min-h-[90vh] flex items-center overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <div class="bg-cover bg-center w-full h-full"
                     style="background-image: url('{{ $s['hero_image'] ?? '' }}')"></div>
                <div class="absolute inset-0 bg-primary/80 mix-blend-multiply backdrop-blur-[2px]"></div>
            </div>

            <div class="relative z-10 w-full max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-on-primary space-y-6">
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-on-primary leading-tight">
                        {{ $s['hero_title'] ?? 'Belajar Renang Bersama ' }}<span class="text-orange-lighter">{{ $s['hero_highlight'] ?? 'Coach Berpengalaman' }}</span>
                    </h1>
                    <p class="font-body text-body-lg text-on-primary/90 max-w-xl">
                        {{ $s['hero_subtitle'] ?? 'Program latihan aman, menyenangkan, dan berorientasi pada pencapaian, didampingi secara khusus oleh coach ahli bersertifikat.' }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="{{ route('register') }}"
                            class="bg-orange text-white px-8 py-4 rounded-lg font-body text-label-md text-center hover:bg-orange-light transition-colors shadow-lg shadow-orange/30">
                            {{ $s['hero_cta_primary'] ?? 'Daftar Sekarang' }}
                        </a>
                        <a href="{{ url('/program') }}"
                            class="bg-surface/10 backdrop-blur-sm border-2 border-on-primary text-on-primary px-8 py-4 rounded-lg font-body text-label-md text-center hover:bg-surface/20 transition-colors">
                            {{ $s['hero_cta_secondary'] ?? 'Lihat Program' }}
                        </a>
                    </div>
                </div>

                <!-- Illustration/Photo -->
                <div class="hidden lg:block relative">
                    <div class="absolute inset-0 bg-orange/20 rounded-[2rem] transform rotate-3 scale-105 z-0 blur-xl"></div>
                    <img class="relative z-10 w-full h-auto rounded-[2rem] shadow-2xl object-cover aspect-[4/3] border-4 border-surface/30"
                         alt="{{ $s['hero_side_image_alt'] ?? 'Anak-anak belajar renang bersama coach ASC' }}"
                         src="{{ $s['hero_side_image'] ?? '' }}">
                </div>
            </div>
        </section>

        <!-- 3. Tentang ASC -->
        <section class="py-16 md:py-24 bg-surface" id="tentang">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <!-- Statistik -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-10 md:mb-16">
                    <div class="bg-primary text-on-primary rounded-2xl p-4 md:p-6 text-center shadow-lg shadow-primary/20">
                        <p class="font-headline text-headline-md md:text-headline-lg font-bold">{{ $s['tentang_years'] ?? '10+' }}</p>
                        <p class="font-body text-label-sm uppercase tracking-wider mt-1 text-on-primary/90">{{ $s['tentang_years_label'] ?? 'Tahun Pengalaman' }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-2xl p-4 md:p-6 text-center border border-outline-variant/30">
                        <p class="font-headline text-headline-md md:text-headline-lg text-primary font-bold">{{ $programs->count() }}</p>
                        <p class="font-body text-label-sm uppercase tracking-wider mt-1 text-on-surface-variant">Program Kelas</p>
                    </div>
                    <div class="bg-surface-container-low rounded-2xl p-4 md:p-6 text-center border border-outline-variant/30">
                        <p class="font-headline text-headline-md md:text-headline-lg text-primary font-bold">{{ $coaches->count() }}</p>
                        <p class="font-body text-label-sm uppercase tracking-wider mt-1 text-on-surface-variant">Coach Profesional</p>
                    </div>
                    <div class="bg-surface-container-low rounded-2xl p-4 md:p-6 text-center border border-outline-variant/30">
                        <p class="font-headline text-headline-md md:text-headline-lg text-primary font-bold">{{ $gallery->count() }}</p>
                        <p class="font-body text-label-sm uppercase tracking-wider mt-1 text-on-surface-variant">Foto Galeri</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
                    <div class="space-y-5 md:space-y-6">
                        <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-primary font-bold">{{ $s['tentang_heading'] ?? 'Tentang Antasena Swimming Club' }}</h2>
                        <p class="text-on-surface-variant font-body text-body-md md:text-body-lg">{{ $s['tentang_text'] ?? 'Berdiri sejak tahun 2010, Antasena Swimming Club (ASC) telah mendedikasikan diri untuk mencetak generasi perenang yang tangguh, percaya diri, dan berprestasi. Kami percaya bahwa berenang bukan sekadar olahraga, melainkan keterampilan hidup (life skill) yang esensial.' }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-surface-container-low rounded-xl p-5 border-t-4 border-orange">
                                <div class="w-10 h-10 rounded-lg bg-orange/15 flex items-center justify-center mb-3">
                                    <span class="material-symbols-outlined text-orange">visibility</span>
                                </div>
                                <h3 class="font-headline text-headline-sm text-primary font-semibold mb-1">Visi</h3>
                                <p class="text-on-surface-variant text-body-sm md:text-body-md">{{ $s['tentang_visi'] ?? 'Menjadi klub renang terbaik yang menginspirasi gaya hidup sehat dan mencetak atlet berprestasi di tingkat nasional maupun internasional.' }}</p>
                            </div>
                            <div class="bg-surface-container-low rounded-xl p-5 border-t-4 border-primary">
                                <div class="w-10 h-10 rounded-lg bg-primary/15 flex items-center justify-center mb-3">
                                    <span class="material-symbols-outlined text-primary">flag</span>
                                </div>
                                <h3 class="font-headline text-headline-sm text-primary font-semibold mb-1">Misi</h3>
                                <ul class="list-disc list-inside text-on-surface-variant text-body-sm md:text-body-md mt-2 space-y-1.5">
                                    @foreach (collect(preg_split('/\r\n|\r|\n/', $s['tentang_misi'] ?? ''))->map(fn ($line) => trim($line))->filter() as $misi)
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
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-primary font-bold">Mengapa Memilih ASC?</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Kami memberikan yang terbaik untuk perkembangan dan kenyamanan proses belajar renang Anda.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <!-- Card 1 -->
                    <div class="bg-surface p-5 sm:p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 sm:flex-col sm:items-center sm:text-center group">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 rounded-full flex items-center justify-center shrink-0 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">sports</span>
                        </div>
                        <div class="min-w-0 sm:text-center">
                            <h3 class="font-headline text-headline-sm text-primary font-semibold mb-1 sm:mb-2">Coach Berpengalaman</h3>
                            <p class="text-on-surface-variant text-body-md">Didampingi oleh pelatih profesional dan bersertifikat yang ahli di bidangnya.</p>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-surface p-5 sm:p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 sm:flex-col sm:items-center sm:text-center group">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 rounded-full flex items-center justify-center shrink-0 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">mood</span>
                        </div>
                        <div class="min-w-0 sm:text-center">
                            <h3 class="font-headline text-headline-sm text-primary font-semibold mb-1 sm:mb-2">Metode Belajar Menyenangkan</h3>
                            <p class="text-on-surface-variant text-body-md">Pendekatan yang ramah dan interaktif membuat proses belajar renang jadi lebih seru.</p>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-surface p-5 sm:p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 sm:flex-col sm:items-center sm:text-center group">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 rounded-full flex items-center justify-center shrink-0 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">pool</span>
                        </div>
                        <div class="min-w-0 sm:text-center">
                            <h3 class="font-headline text-headline-sm text-primary font-semibold mb-1 sm:mb-2">Fasilitas Lengkap</h3>
                            <p class="text-on-surface-variant text-body-md">Kolam renang berstandar dengan fasilitas pendukung yang memadai untuk berlatih.</p>
                        </div>
                    </div>
                    <!-- Card 4 -->
                    <div class="bg-surface p-5 sm:p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 sm:flex-col sm:items-center sm:text-center group">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 rounded-full flex items-center justify-center shrink-0 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">health_and_safety</span>
                        </div>
                        <div class="min-w-0 sm:text-center">
                            <h3 class="font-headline text-headline-sm text-primary font-semibold mb-1 sm:mb-2">Aman dan Nyaman</h3>
                            <p class="text-on-surface-variant text-body-md">Prioritas utama pada keselamatan siswa dengan pengawasan ketat selama latihan.</p>
                        </div>
                    </div>
                    <!-- Card 5 -->
                    <div class="bg-surface p-5 sm:p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 sm:flex-col sm:items-center sm:text-center group">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 rounded-full flex items-center justify-center shrink-0 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
                        </div>
                        <div class="min-w-0 sm:text-center">
                            <h3 class="font-headline text-headline-sm text-primary font-semibold mb-1 sm:mb-2">Jadwal Fleksibel</h3>
                            <p class="text-on-surface-variant text-body-md">Pilihan waktu latihan yang dapat disesuaikan dengan kesibukan Anda.</p>
                        </div>
                    </div>
                    <!-- Card 6 -->
                    <div class="bg-surface p-5 sm:p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 sm:flex-col sm:items-center sm:text-center group">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-primary/10 rounded-full flex items-center justify-center shrink-0 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">analytics</span>
                        </div>
                        <div class="min-w-0 sm:text-center">
                            <h3 class="font-headline text-headline-sm text-primary font-semibold mb-1 sm:mb-2">E-Raport</h3>
                            <p class="text-on-surface-variant text-body-md">Pantau perkembangan kemampuan renang secara transparan melalui laporan digital berkala.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Program Kelas -->
        <section class="py-16 md:py-24 bg-surface" id="program">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-primary font-bold">{{ $s['program_heading'] ?? 'Program Kelas Kami' }}</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">{{ $s['program_subtitle'] ?? 'Pilih program yang paling sesuai dengan kebutuhan dan target Anda.' }}</p>
                </div>
                @if ($programs->isNotEmpty())
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
                            @foreach ($programs as $program)
                                <div class="shrink-0 w-[85%] sm:w-[48%] lg:w-[31.5%] xl:w-[23%]">
                                    <div class="h-full bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 shadow-md hover:shadow-xl transition-shadow flex flex-col">
                                        <div class="flex items-start justify-between gap-2 mb-4">
                                            <h3 class="font-headline text-headline-sm text-primary font-bold">{{ $program->name }}</h3>
                                            @if ($program->badge)
                                                <span class="shrink-0 bg-orange text-white text-[10px] font-bold px-2.5 py-1 rounded-full mt-0.5">{{ $program->badge }}</span>
                                            @endif
                                        </div>
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
                                            {{ $program->button_label ?? 'Pilih Program' }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        <button type="button" @click="prev()" aria-label="Program sebelumnya"
                            class="hidden md:flex absolute -left-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-primary text-on-primary items-center justify-center shadow-lg hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button type="button" @click="next()" aria-label="Program berikutnya"
                            class="hidden md:flex absolute -right-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-primary text-on-primary items-center justify-center shadow-lg hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                @else
                    <p class="text-center text-on-surface-variant">Belum ada program.</p>
                @endif
            </div>
        </section>

        <!-- 6. Jadwal Latihan -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="jadwal">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-primary font-bold">{{ $s['jadwal_heading'] ?? 'Jadwal Latihan Reguler' }}</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">{{ $s['jadwal_subtitle'] ?? 'Untuk jadwal Private dan Mini Private dapat didiskusikan langsung dengan Coach.' }}</p>
                </div>
                <!-- Mobile: kartu jadwal -->
                <div class="grid grid-cols-1 gap-3 md:hidden">
                    @forelse ($jadwalRows as $row)
                        <div class="bg-surface rounded-xl border border-outline-variant/30 shadow-sm p-4 flex gap-4 items-start">
                            <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary text-2xl">calendar_month</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-headline text-headline-sm text-primary font-semibold">{{ $row['day'] }}</h3>
                                <p class="text-on-surface-variant text-body-sm mt-0.5">{{ $row['program'] }}</p>
                                <p class="flex items-center gap-1 text-body-sm mt-1.5">
                                    <span class="material-symbols-outlined text-orange text-base">schedule</span>
                                    <span>{{ $row['time'] }}</span>
                                </p>
                                <p class="flex items-start gap-1 text-body-sm mt-1 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-orange text-base">location_on</span>
                                    <span>{{ $row['location'] }}</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-on-surface-variant">Jadwal belum diatur.</p>
                    @endforelse
                </div>

                <!-- Desktop: tabel -->
                <div class="hidden md:block overflow-x-auto rounded-xl border border-outline-variant/30 shadow-sm">
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
                                <tr class="hover:bg-surface-container-low transition-colors {{ $loop->last ? '' : 'border-b border-outline-variant/20' }}">
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

        <!-- 7. Coach Kami -->
        <section class="py-16 md:py-24 bg-surface">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-primary font-bold">Temui Coach Kami</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Dilatih langsung oleh para profesional bersertifikat yang berdedikasi tinggi.</p>
                </div>
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
                                <div class="shrink-0 w-[45%] sm:w-[48%] lg:w-[31.5%]">
                                    <div class="h-full bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md group">
                                        <div class="relative overflow-hidden aspect-[3/4]">
                                            @if ($coach->photo_url)
                                                <img alt="{{ $coach->name }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                     src="{{ $coach->photo_url }}">
                                            @else
                                                <div class="w-full h-full bg-surface-container flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-on-surface-variant text-[64px]">person</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4 sm:p-6">
                                            <h3 class="font-headline text-headline-sm text-primary font-bold">{{ $coach->name }}</h3>
                                            <p class="text-orange font-body text-label-md mb-2">{{ $coach->position }}</p>
                                            <p class="text-on-surface-variant text-body-sm">{{ $coach->description }}</p>
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
                    <p class="col-span-full text-center text-on-surface-variant">Belum ada coach.</p>
                @endif
            </div>
        </section>

        <!-- 8. Galeri -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="galeri">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-primary font-bold">Galeri Kegiatan</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Momen-momen seru dan pencapaian membanggakan siswa-siswi ASC.</p>
                </div>
                @if ($gallery->isNotEmpty())
                    <!-- Mobile: carousel geser -->
                    <div class="md:hidden flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        @foreach ($gallery as $image)
                            <a href="{{ url('/galeri') }}"
                               class="snap-start shrink-0 w-[86%] block bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md group">
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    <img alt="{{ $image->title ?? 'Galeri ASC' }}" loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                         src="{{ $image->image_url }}">
                                    @if ($image->category)
                                        <span class="absolute top-3 left-3 bg-orange text-white text-xs font-bold px-3 py-1 rounded-full shadow">{{ $image->category }}</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    @if ($image->title)
                                        <h3 class="font-headline text-headline-sm text-primary font-semibold">{{ $image->title }}</h3>
                                    @endif
                                    @if ($image->description)
                                        <p class="text-on-surface-variant text-body-sm mt-1 line-clamp-2">{{ $image->description }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <p class="text-center text-on-surface-variant text-body-sm mt-3 md:hidden">Geser untuk melihat foto lainnya</p>

                    <!-- Desktop: mosaik -->
                    <div class="hidden md:grid grid-cols-2 md:grid-cols-4 auto-rows-[120px] sm:auto-rows-[150px] md:auto-rows-[220px] gap-3 md:gap-4">
                        @foreach ($gallery as $image)
                            <a href="{{ url('/galeri') }}"
                               class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-shadow {{ $loop->index === 0 ? 'col-span-2 row-span-2' : ($loop->index % 4 === 1 ? 'col-span-2' : '') }}">
                                <img alt="{{ $image->title ?? 'Galeri ASC' }}" loading="lazy"
                                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     src="{{ $image->image_url }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/20 transition-colors duration-300 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-4xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">zoom_in</span>
                                </div>
                                @if ($image->category)
                                    <span class="absolute top-2 left-2 md:top-3 md:left-3 bg-orange text-white text-[10px] md:text-xs font-bold px-2 md:px-3 py-1 rounded-full shadow">{{ $image->category }}</span>
                                @endif
                                <div class="absolute inset-x-0 bottom-0 p-2 md:p-4">
                                    @if ($image->title)
                                        <h3 class="text-white font-headline text-xs sm:text-sm md:text-base font-semibold leading-tight">{{ $image->title }}</h3>
                                    @endif
                                    @if ($image->description)
                                        <p class="text-white/80 text-[10px] sm:text-xs md:text-sm mt-0.5 md:mt-1 line-clamp-2">{{ $image->description }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="text-center mt-8">
                        <a href="{{ url('/galeri') }}"
                           class="inline-flex items-center gap-2 bg-primary text-on-primary font-body text-label-md px-6 py-3 rounded-lg hover:bg-primary-container transition-colors shadow-lg shadow-primary/20">
                            Lihat Semua Galeri
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                @else
                    <p class="text-center text-on-surface-variant">Belum ada foto galeri.</p>
                @endif
            </div>
        </section>

        <!-- 9. FAQ -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="faq">
            <div class="max-w-3xl mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-primary font-bold">Pertanyaan Umum (FAQ)</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4">Jawaban atas beberapa pertanyaan yang sering diajukan.</p>
                </div>
                <div class="space-y-4">
                    <!-- Item 1 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-4 bg-surface text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Bagaimana cara mendaftar di ASC?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-4 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Pendaftaran dapat dilakukan secara online dengan mengklik tombol "Daftar" di website ini, atau Anda bisa datang langsung ke meja pendaftaran kami di Kolam Renang Universitas Lampung pada jam operasional.
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-4 bg-surface text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Mulai usia berapa anak bisa ikut kelas renang?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-4 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Kami menerima siswa mulai dari usia 4 tahun untuk program reguler/mini reguler anak-anak. Untuk usia di bawah 4 tahun, disarankan mengikuti program private dengan pendampingan khusus.
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-4 bg-surface text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Bagaimana sistem pembayaran biayanya?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-4 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Pembayaran dapat dilakukan melalui transfer bank, e-wallet (GoPay, OVO, Dana), atau secara tunai di lokasi pendaftaran. Pembayaran dilakukan di awal sebelum sesi pertama dimulai.
                        </div>
                    </div>
                    <!-- Item 4 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-4 bg-surface text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Apakah biaya sudah termasuk tiket masuk kolam?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-4 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Ya, seluruh biaya program kelas (Private, Reguler, dll) yang tercantum sudah termasuk biaya tiket masuk kolam renang untuk siswa selama sesi latihan berlangsung. Pendamping/orang tua yang masuk area kolam namun tidak berenang mungkin dikenakan tarif masuk reguler kolam renang (bukan dari pihak ASC).
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 11. Call to Action -->
        <section class="py-16 md:py-24 bg-surface">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="bg-primary rounded-3xl p-10 md:p-16 text-center text-on-primary shadow-2xl relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary-container rounded-full mix-blend-multiply filter blur-3xl opacity-70 transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange/30 rounded-full mix-blend-overlay filter blur-3xl opacity-50 transform -translate-x-1/2 translate-y-1/2"></div>
                    <div class="relative z-10">
                        <h2 class="font-headline text-headline-lg-mobile md:text-headline-xl font-bold mb-4">Siap Memulai Perjalanan Renang Anda?</h2>
                        <p class="font-body text-body-lg max-w-2xl mx-auto mb-8 text-on-primary/90">Bergabunglah dengan ratusan siswa lainnya yang telah merasakan manfaat belajar renang bersama Antasena Swimming Club. Daftar sekarang dan jadilah perenang tangguh!</p>
                        <a href="{{ route('register') }}"
                            class="inline-block bg-orange text-white px-8 py-4 rounded-xl font-body text-label-md hover:bg-orange-light transition-colors shadow-lg shadow-orange/40 hover:scale-105 transform duration-200">
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
