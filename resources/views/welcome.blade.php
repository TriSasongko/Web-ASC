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

        <!-- 2. Hero Section -->
        <section class="relative pt-32 pb-16 md:pb-24 min-h-[90vh] flex items-center overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <div class="bg-cover bg-center w-full h-full"
                     style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD4cneCRj7gVWVu5MoJSEhNOT2StGeVi4mEs0NzDt4j1OYkICwUtTlG34CCrhjYMwIpfst55mmAikPMfxcDcK6d1lRiMN1T1Z19N1fj_uIOsnkK5bjOOdNzAx-E1lGEXFF4kLGImmMHVkKKapB5CWSRcKt01UrlKnVgjTstB46ckJtLbIg0rKS4nFkTtJgn1dK5saaGvQn-0xCmi6IRQUth5XPAT4TK1sYC-fTnSwI5ZIjbNPZA_jgn')"></div>
                <div class="absolute inset-0 bg-primary/80 mix-blend-multiply backdrop-blur-[2px]"></div>
            </div>

            <div class="relative z-10 w-full max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-on-primary space-y-6">
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-on-primary leading-tight">
                        Belajar Renang Bersama <span class="text-orange-lighter">Coach Berpengalaman</span>
                    </h1>
                    <p class="font-body text-body-lg text-on-primary/90 max-w-xl">
                        Program latihan aman, menyenangkan, dan berorientasi pada pencapaian, didampingi secara khusus oleh coach ahli bersertifikat.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="{{ route('register') }}"
                            class="bg-orange text-white px-8 py-4 rounded-lg font-body text-label-md text-center hover:bg-orange-light transition-colors shadow-lg shadow-orange/30">
                            Daftar Sekarang
                        </a>
                        <a href="#program"
                            class="bg-surface/10 backdrop-blur-sm border-2 border-on-primary text-on-primary px-8 py-4 rounded-lg font-body text-label-md text-center hover:bg-surface/20 transition-colors">
                            Lihat Program
                        </a>
                    </div>
                </div>

                <!-- Illustration/Photo -->
                <div class="hidden lg:block relative">
                    <div class="absolute inset-0 bg-orange/20 rounded-[2rem] transform rotate-3 scale-105 z-0 blur-xl"></div>
                    <img class="relative z-10 w-full h-auto rounded-[2rem] shadow-2xl object-cover aspect-[4/3] border-4 border-surface/30"
                         alt="Anak-anak belajar renang bersama coach ASC"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwN_z28IRGM_zCNn-d4plYCjWDys2KEkgg-mLNuv7eSWtkTBNKbHWlgXM30wsMxpc6WaGOeAjJ1zshXMZKwbYoo1OJKE0pBoFk0CidghkeUEoTZmyW49KD-aaF-oulIWzmxHFG2eT0xk5OaWjY9527tTyD4LMsL5OxgjVMvV62xAP4d7SSRiP4DUM54m72iBDRQZilVq5VVA1-yQXenm9TCxm62ccbOiqIhbSKeZSh9oIG3KJoC3ES">
                </div>
            </div>
        </section>

        <!-- 3. Tentang ASC -->
        <section class="py-16 md:py-24 bg-surface" id="tentang">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6">
                        <h2 class="font-headline text-headline-lg text-primary font-bold">Tentang Antasena Swimming Club</h2>
                        <p class="text-on-surface-variant font-body text-body-lg">Berdiri sejak tahun 2010, Antasena Swimming Club (ASC) telah mendedikasikan diri untuk mencetak generasi perenang yang tangguh, percaya diri, dan berprestasi. Kami percaya bahwa berenang bukan sekadar olahraga, melainkan keterampilan hidup (life skill) yang esensial.</p>
                        <div class="bg-surface-container-low p-6 rounded-xl border-l-4 border-orange space-y-4">
                            <div>
                                <h3 class="font-headline text-headline-sm text-primary font-semibold">Visi</h3>
                                <p class="text-on-surface-variant text-body-md mt-2">Menjadi klub renang terbaik yang menginspirasi gaya hidup sehat dan mencetak atlet berprestasi di tingkat nasional maupun internasional.</p>
                            </div>
                            <div>
                                <h3 class="font-headline text-headline-sm text-primary font-semibold">Misi</h3>
                                <ul class="list-disc list-inside text-on-surface-variant text-body-md mt-2 space-y-1">
                                    <li>Menyediakan metode pelatihan yang aman, terstruktur, dan menyenangkan.</li>
                                    <li>Mengembangkan potensi setiap individu melalui pendekatan personal.</li>
                                    <li>Menumbuhkan karakter disiplin, sportivitas, dan pantang menyerah.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <img alt="Kegiatan latihan renang di ASC"
                             class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3]"
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuAmiadFbEY1R71f-xV10Cxoiqukf6VBfRXqj7WAZDt-9S6Qsaz14Ulc-fu2uW0Musp9wb2TDiJAnbNlef8AaXiOgPpxm8_YD4j-2Q2M_DCiQw4xeQVj4ZJ_loKzBmTJdlo7aKKbvLKtMznAVcrjHc8KykjKgfGvluY1i6IhJlR8AH0AaM-RitVo7ZINwDDQFJf99rWANhjcTy3ywYqqXGoy8sdBD396_dEBeG7v-MPlOLBzu9KEVlrz">
                        <div class="absolute -bottom-6 -left-6 bg-primary text-on-primary p-6 rounded-xl shadow-lg hidden md:block">
                            <p class="font-headline text-headline-xl text-orange">10+</p>
                            <p class="font-body text-label-sm uppercase tracking-wider">Tahun Pengalaman</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Keunggulan ASC -->
        <section class="py-16 md:py-24 bg-surface-container-lowest">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Mengapa Memilih ASC?</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Kami memberikan yang terbaik untuk perkembangan dan kenyamanan proses belajar renang Anda.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">sports</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Coach Berpengalaman</h3>
                        <p class="text-on-surface-variant text-body-md">Didampingi oleh pelatih profesional dan bersertifikat yang ahli di bidangnya.</p>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">mood</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Metode Belajar Menyenangkan</h3>
                        <p class="text-on-surface-variant text-body-md">Pendekatan yang ramah dan interaktif membuat proses belajar renang jadi lebih seru.</p>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">pool</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Fasilitas Lengkap</h3>
                        <p class="text-on-surface-variant text-body-md">Kolam renang berstandar dengan fasilitas pendukung yang memadai untuk berlatih.</p>
                    </div>
                    <!-- Card 4 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">health_and_safety</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Aman dan Nyaman</h3>
                        <p class="text-on-surface-variant text-body-md">Prioritas utama pada keselamatan siswa dengan pengawasan ketat selama latihan.</p>
                    </div>
                    <!-- Card 5 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">Jadwal Fleksibel</h3>
                        <p class="text-on-surface-variant text-body-md">Pilihan waktu latihan yang dapat disesuaikan dengan kesibukan Anda.</p>
                    </div>
                    <!-- Card 6 -->
                    <div class="bg-surface p-6 rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center group">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl">analytics</span>
                        </div>
                        <h3 class="font-headline text-headline-sm text-primary font-semibold mb-2">E-Raport</h3>
                        <p class="text-on-surface-variant text-body-md">Pantau perkembangan kemampuan renang secara transparan melalui laporan digital berkala.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Program Kelas -->
        <section class="py-16 md:py-24 bg-surface" id="program">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Program Kelas Kami</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Pilih program yang paling sesuai dengan kebutuhan dan target Anda.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    <!-- Private -->
                    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 shadow-md hover:shadow-xl transition-shadow flex flex-col">
                        <div class="mb-4">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Private</h3>
                            <p class="text-on-surface-variant text-label-sm mt-1">1 Coach : 1 Siswa</p>
                        </div>
                        <div class="mb-6">
                            <span class="font-headline text-headline-lg text-orange font-bold">Rp500rb</span>
                            <span class="text-on-surface-variant text-body-md">/sesi</span>
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Fokus intensif 1 on 1</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Jadwal sangat fleksibel</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Progres lebih cepat</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}"
                            class="w-full text-center bg-primary text-on-primary py-2 rounded-lg font-body text-label-md hover:bg-primary-container transition-colors mt-auto">
                            Pilih Program
                        </a>
                    </div>
                    <!-- Mini Private -->
                    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 shadow-md hover:shadow-xl transition-shadow flex flex-col relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-orange text-white text-xs font-bold px-3 py-1 rounded-bl-lg">POPULER</div>
                        <div class="mb-4">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Mini Private</h3>
                            <p class="text-on-surface-variant text-label-sm mt-1">1 Coach : 2-3 Siswa</p>
                        </div>
                        <div class="mb-6">
                            <span class="font-headline text-headline-lg text-orange font-bold">Rp300rb</span>
                            <span class="text-on-surface-variant text-body-md">/sesi</span>
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Cocok untuk keluarga/teman</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Perhatian tetap optimal</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Lebih hemat</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}"
                            class="w-full text-center bg-primary text-on-primary py-2 rounded-lg font-body text-label-md hover:bg-primary-container transition-colors mt-auto">
                            Pilih Program
                        </a>
                    </div>
                    <!-- Reguler -->
                    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 shadow-md hover:shadow-xl transition-shadow flex flex-col">
                        <div class="mb-4">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Reguler</h3>
                            <p class="text-on-surface-variant text-label-sm mt-1">1 Coach : Max 8 Siswa</p>
                        </div>
                        <div class="mb-6">
                            <span class="font-headline text-headline-lg text-orange font-bold">Rp350rb</span>
                            <span class="text-on-surface-variant text-body-md">/bulan</span>
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>4x pertemuan/bulan</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Belajar bersama teman sebaya</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Kurikulum terstruktur</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}"
                            class="w-full text-center bg-outline text-on-primary py-2 rounded-lg font-body text-label-md hover:bg-outline-variant transition-colors mt-auto">
                            Pilih Program
                        </a>
                    </div>
                    <!-- Mini Reguler -->
                    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 shadow-md hover:shadow-xl transition-shadow flex flex-col">
                        <div class="mb-4">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Mini Reguler</h3>
                            <p class="text-on-surface-variant text-label-sm mt-1">1 Coach : Max 5 Siswa</p>
                        </div>
                        <div class="mb-6">
                            <span class="font-headline text-headline-lg text-orange font-bold">Rp200rb</span>
                            <span class="text-on-surface-variant text-body-md">/sesi</span>
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Kelompok kecil</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Fokus lebih baik dari reguler</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Interaksi sosial terjaga</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}"
                            class="w-full text-center bg-outline text-on-primary py-2 rounded-lg font-body text-label-md hover:bg-outline-variant transition-colors mt-auto">
                            Pilih Program
                        </a>
                    </div>
                    <!-- Kompetitif -->
                    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 shadow-md hover:shadow-xl transition-shadow flex flex-col border-t-4 border-t-primary">
                        <div class="mb-4">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Kompetitif</h3>
                            <p class="text-on-surface-variant text-label-sm mt-1">Program Khusus Atlet</p>
                        </div>
                        <div class="mb-6">
                            <span class="font-headline text-headline-lg text-orange font-bold">Rp300rb</span>
                            <span class="text-on-surface-variant text-body-md">/bulan</span>
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Latihan intensif</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Persiapan kejuaraan</span>
                            </li>
                            <li class="flex items-start gap-2 text-on-surface-variant text-body-md">
                                <span class="material-symbols-outlined text-primary text-sm mt-1">check_circle</span>
                                <span>Evaluasi berkala ketat</span>
                            </li>
                        </ul>
                        <a href="{{ route('register') }}"
                            class="w-full text-center bg-primary text-on-primary py-2 rounded-lg font-body text-label-md hover:bg-primary-container transition-colors mt-auto">
                            Pilih Program
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. Jadwal Latihan -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="jadwal">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Jadwal Latihan Reguler</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Untuk jadwal Private dan Mini Private dapat didiskusikan langsung dengan Coach.</p>
                </div>
                <div class="overflow-x-auto rounded-xl border border-outline-variant/30 shadow-sm">
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
                            <tr class="hover:bg-surface-container-low transition-colors border-b border-outline-variant/20">
                                <td class="p-4 font-semibold text-primary">Senin &amp; Rabu</td>
                                <td class="p-4">15:30 - 17:00</td>
                                <td class="p-4">Reguler Pemula &amp; Lanjutan</td>
                                <td class="p-4">Kolam Renang Universitas Lampung</td>
                            </tr>
                            <tr class="hover:bg-surface-container-low transition-colors border-b border-outline-variant/20">
                                <td class="p-4 font-semibold text-primary">Selasa &amp; Kamis</td>
                                <td class="p-4">16:00 - 18:00</td>
                                <td class="p-4">Kompetitif (Atlet)</td>
                                <td class="p-4">Kolam Renang Universitas Lampung</td>
                            </tr>
                            <tr class="hover:bg-surface-container-low transition-colors border-b border-outline-variant/20">
                                <td class="p-4 font-semibold text-primary">Jumat</td>
                                <td class="p-4">15:00 - 16:30</td>
                                <td class="p-4">Mini Reguler</td>
                                <td class="p-4">Kolam Renang Universitas Lampung</td>
                            </tr>
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="p-4 font-semibold text-primary">Sabtu &amp; Minggu</td>
                                <td class="p-4">07:00 - 09:00</td>
                                <td class="p-4">Semua Kelas Reguler</td>
                                <td class="p-4">Kolam Renang Universitas Lampung</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- 7. Coach Kami -->
        <section class="py-16 md:py-24 bg-surface">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Temui Coach Kami</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Dilatih langsung oleh para profesional bersertifikat yang berdedikasi tinggi.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Coach 1 -->
                    <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md group">
                        <div class="relative overflow-hidden aspect-[3/4]">
                            <img alt="Coach Budi"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuCeo77mtiXI7SW9GOwmS9QuwGRGDjxWA16W2Ls0kayebuujMQ-kwoohS4jdKR_-7Vzb1hhl-CIspsO1bGP5np4UGcprJ-pWWmxTy6gmIdmuKUFZtliz_I1guflrgDtYyIskNfXeSlwv3nWADfTQi69Ijslnr-I8dfc0s_GbYJkUouVn7NKWY5pz_0CKdfZcN4fHHx4flGed_q9XRj1lSuM-yyvkqjVg48WjdZpMbeAKsYA4Dux67O_7">
                        </div>
                        <div class="p-6">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Budi Santoso</h3>
                            <p class="text-orange font-body text-label-md mb-2">Head Coach</p>
                            <p class="text-on-surface-variant text-body-sm">Mantan atlet nasional dengan pengalaman melatih lebih dari 15 tahun. Spesialisasi pada program kompetitif dan pembentukan teknik dasar.</p>
                        </div>
                    </div>
                    <!-- Coach 2 -->
                    <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md group">
                        <div class="relative overflow-hidden aspect-[3/4]">
                            <img alt="Coach Siti"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuCaujJ0KbaRnEnDCCceyva2PTCxK2SzHQrlIIMScWeRyLcPR3vJgwl_sg8ujslrvIlCKh6rgxrDZ2PUZLVUq2-fJfVOR7qoQJiPl2VfZ-miTCg4pbhCb9MGiQB-1MPMnpAlknxNVUfTcyIEpZWWV0GrrjS7U4jTQosEkD4NCkytcIHfgzGTjkVgIFHc4fulH1hZrXgq6iqKGbWfZAPEYrAcoYwx_8fDF_RDuM4WjzsRv9yLG1V7k0ZU">
                        </div>
                        <div class="p-6">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Siti Rahmawati</h3>
                            <p class="text-orange font-body text-label-md mb-2">Senior Coach</p>
                            <p class="text-on-surface-variant text-body-sm">Ahli dalam pendekatan anak-anak usia dini. Sabar, telaten, dan selalu membuat suasana belajar renang menjadi sangat menyenangkan.</p>
                        </div>
                    </div>
                    <!-- Coach 3 -->
                    <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md group">
                        <div class="relative overflow-hidden aspect-[3/4]">
                            <img alt="Coach Andi"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuABPMGvLp2zbPzfSSw72GWAj1BCTS-N1vhJs-lr-7heikBLSQUEpzzl9sjGIyItbNnN350Yt1RFNUi1U5V4TR-9kkRvz9aolqzHzCIoGR-xtTFa3h88PJ69jYKqE2rhlpYYfHLKL1OJFYje6svxeIgbaBva-AuEYcLD4FIoYQExQ_eCqTydZZTj9YHqDIil46UtsMGaZ6koqcPz2Q5n7wQOV_NQoel8pnNxazRHNaibmP_MFjfmaxRE">
                        </div>
                        <div class="p-6">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Andi Pratama</h3>
                            <p class="text-orange font-body text-label-md mb-2">Coach</p>
                            <p class="text-on-surface-variant text-body-sm">Spesialis gaya bebas dan kupu-kupu. Fokus pada peningkatan stamina dan perbaikan detail teknik berenang.</p>
                        </div>
                    </div>
                    <!-- Coach 4 -->
                    <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md group">
                        <div class="relative overflow-hidden aspect-[3/4]">
                            <img alt="Coach Maya"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5WDNHwIogwoeeLcW4e35W2GKQWwtA4u-GZN9mEKMZ47HOvmQscThegdvkyQ-0uQfIabYUf7wfBppnzVqY-kAy2hwnxMYpIQyOOHxLVMUmAVuboM9MRgvrmBNB7JP5W87-lq4XfnxwT4iD4jk3OeN6oXo1azJxuDAHi4siYGDBsAZYKwD3HBQ0MUcHewHbqZCOhf_6quLQbIGW3aLZR41aGMnF_kUi6jHRZP_4U0BhYv1cayC0p5ll">
                        </div>
                        <div class="p-6">
                            <h3 class="font-headline text-headline-sm text-primary font-bold">Maya Sari</h3>
                            <p class="text-orange font-body text-label-md mb-2">Coach</p>
                            <p class="text-on-surface-variant text-body-sm">Bersertifikat khusus penyelamatan air (water rescue). Sangat memperhatikan aspek keamanan dan keselamatan selama berlatih.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. Galeri -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="galeri">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Galeri Kegiatan</h2>
                    <p class="text-on-surface-variant font-body text-body-lg mt-4 max-w-2xl mx-auto">Momen-momen seru dan pencapaian membanggakan siswa-siswi ASC.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <a class="block relative group overflow-hidden rounded-xl aspect-square" href="#galeri">
                        <img alt="Galeri 1" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDCFjAR19WNvx9tJfzMIQU7C-ZAA5JTQpUY1ebjhg4yfBmGOSy4fAOhfZRTEYT17Yg6PyphQKOHSho3UzsYKpqE5l4T7_MEVAb_JKw0jLrAvepaQCy8svP5spDYcLc8EqHPJeiOf4ZrwlSgMgmV2AFw56WMTMYnSFvCjyJIm_L5TTQ3sOtwELddhZnBbIrE2L6LTcCZltanivo6URhU2ojZdVrDvqYouLdku9D7FU7IwD_Xwpe4jdiD">
                        <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                        </div>
                    </a>
                    <a class="block relative group overflow-hidden rounded-xl aspect-square" href="#galeri">
                        <img alt="Galeri 2" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4P-5_4Y5GizDpwCwV9xBjXIWg8mUjFl81Omq9KtY2DyCdDv6au1RXmLIDVkFsHCe4T6t6VO3DTBs7RV-ZM4iK4lePLqtKiUXSaD71dIUT04MZZq-2j7uRq5u-QH8geBTh5CKv6OShNx8O9rMdUyVYWaUS5vjYtzDCedZH5LK_vpZjy97dDwieHZeXyrpE-4EQRgQadb7WO4ucrs2w28xudGaOVqPfrFui-1O1z_axAI7hpT882Wcn">
                        <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                        </div>
                    </a>
                    <a class="block relative group overflow-hidden rounded-xl aspect-square" href="#galeri">
                        <img alt="Galeri 3" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzigC_t94uOPkI0tnm69pMGC7_smYqXWZ9SRMSohLKzHI9zmLeNlV1U4hMEaGkI4-9-CSrf425YQw0HE_lHaPvyilkF3al3V66j6mSHg4UqZYNPp8IF8MY-SSGpV7XeXkA2Xr4AH2_JRHP4gh9kdVZp-czfmD_7poetKY_alGNE005YUHMxZx1LnRPLUTrYJu6OwhxME2bOFAdQnDBuIFmGibLY29oKNCiVU8ER7pnBG0hB1r9vO9Y">
                        <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                        </div>
                    </a>
                    <a class="block relative group overflow-hidden rounded-xl aspect-square" href="#galeri">
                        <img alt="Galeri 4" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBNHW2v0H8rjqk_809Rul0iwTiPtoVHs9C3DtxxogAITRO9qT0nVMtYSK_qb0s6SdGbiUOUYiBuzfuIZfH-_Xba8vfmojv-IkMOBWJ3hoQbe1AWLo4YyG2NlxBkDYAoyemuezVNO_LqytCC4MR2qbKZFhccx15kjD6rSJzZ0FnQWzZGq5ik6Hece0tZzqmCmgJwc_y_-JEeTkbQ-4YyjAWL1O_PI5tV5RnV7Wb7NB1Zh_4owC63cHYv">
                        <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                        </div>
                    </a>
                    <a class="block relative group overflow-hidden rounded-xl aspect-square" href="#galeri">
                        <img alt="Galeri 5" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAfmTpBJqswEQZ9ekHqNMgu6_I0z0dO8R2ImxD8Psl_jF9e_5wU7E5NxttMx0cckm2tsZXtcuQTRCqLQNwAg3uMhQf22bl-MD4hwzyYnKkQWuiTzSrfk6VcX3jjb2HzkYJFmbhbJF6qa5KC8izRTOmX2wRSnbvPTEgZ937jh1rmZ41MdgqFyqE7pjquuiJuRL0oxA-MUjCTZtys4qcwQXEFNnAYgS2RnIYDMUEMuQre3yEaQSNlKkPf">
                        <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                        </div>
                    </a>
                    <a class="block relative group overflow-hidden rounded-xl aspect-square" href="#galeri">
                        <img alt="Galeri 6" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCDuO0KiEFUUcQrDEco2eDycR7OYs6kZTdubh39OM_JiZjFk4h0Ay8OYAtNEnAwuEkkgZrZhu8qmqcsXV6XUXH66Tw4fVbuLHqN5rcLqFGqPvXpmUeIF6rDjDkNmOvE4dtYUqnLaaBjtJx10wl0R79T7NOzn_hm-WmT3MB1QNhsBd5ddoAYt17Fcl-g6_auQ1QLUBQVIb4ahPFLtuUqJbRClVASAzSuo2nqmBHyVLKklP8Nd0BA_vGS">
                        <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                        </div>
                    </a>
                    <a class="block relative group overflow-hidden rounded-xl aspect-square" href="#galeri">
                        <img alt="Galeri 7" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBThTzuWI5rBBwhO7oDgVytmutMzhy3fa7Zm69UlhWl7gWjOqKFvCECbsPdF8R8IZ1DhKXv_Y4wyeSzB3CMk8SjfvPPJDahjUeE41RaEvk67Gga93EHDVDrt8oL4b68MYzox5p3c6m2e3rzjFEICWtJdjuJqAW8noPVIX-eLmX2x0miuZV73yWsz5SJpob1M6XcliuO-Rwhd5TPgpEKlnNq45lb4WaPjRYELlJ2-HTTSUEMT8p5i1pC">
                        <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                        </div>
                    </a>
                    <a class="block relative group overflow-hidden rounded-xl aspect-square" href="#galeri">
                        <img alt="Galeri 8" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAYOHuZo9ysAmIHkpPED6uJw-hDQRSvOW6Um4uUIDoLcVbB58rg8Lss2YoEARULU8ZUsTxK5ktLjEx79uybYNkz8fiUScpUxDN5k8pq6Ws1TovgMVZ7SLNBS2XemjSnzuEmUrJJhDLiCOHNe3fw3vJoaJiDM8_TYZRxNfA_EbV15Y3y8oiG2Yl2ZqPUNJ3FJloDyrggep7vcEUtuDYjozhZpc_WRqfGEVnPeIwIMSiGhGlJKOeZcLHd">
                        <div class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-4xl">zoom_in</span>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- 9. Testimoni -->
        <section class="py-16 md:py-24 bg-surface overflow-hidden" x-data="{ currentSlide: 0, slides: [0, 1, 2] }">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop text-center">
                <h2 class="font-headline text-headline-lg text-primary font-bold mb-12">Apa Kata Mereka?</h2>
                <div class="relative w-full max-w-4xl mx-auto">
                    <!-- Slides -->
                    <div class="overflow-hidden relative h-64 md:h-48">
                        <div class="flex transition-transform duration-500 ease-out h-full" x-bind:style="`transform: translateX(-${currentSlide * 100}%)`">
                            <!-- Testimonial 1 -->
                            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center justify-center">
                                <div class="flex text-orange mb-4">
                                    <span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span>
                                </div>
                                <p class="text-on-surface-variant font-body text-body-lg italic mb-6">"Anak saya awalnya sangat takut air. Berkat Coach Siti dari ASC, dalam 2 bulan dia sudah berani menyelam dan belajar gaya bebas. Terima kasih ASC!"</p>
                                <p class="font-headline text-headline-sm text-primary font-semibold">- Ibu Nisa, Orang tua dari Dito (7 tahun)</p>
                            </div>
                            <!-- Testimonial 2 -->
                            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center justify-center">
                                <div class="flex text-orange mb-4">
                                    <span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span>
                                </div>
                                <p class="text-on-surface-variant font-body text-body-lg italic mb-6">"Program kompetitifnya sangat terstruktur. Latihannya disiplin tapi coach-nya tetap supportif. Anak saya berhasil meraih medali di kejuaraan daerah bulan lalu."</p>
                                <p class="font-headline text-headline-sm text-primary font-semibold">- Bapak Rio, Orang tua dari Keiza (12 tahun)</p>
                            </div>
                            <!-- Testimonial 3 -->
                            <div class="w-full flex-shrink-0 px-4 flex flex-col items-center justify-center">
                                <div class="flex text-orange mb-4">
                                    <span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span><span class="material-symbols-outlined filled">star</span>
                                </div>
                                <p class="text-on-surface-variant font-body text-body-lg italic mb-6">"Fasilitas kolamnya bersih dan aman. Sistem E-Raport juga sangat membantu saya memantau perkembangan belajar renang anak-anak. Highly recommended!"</p>
                                <p class="font-headline text-headline-sm text-primary font-semibold">- Ibu Sarah, Orang tua dari kembar Ali &amp; Alif</p>
                            </div>
                        </div>
                    </div>
                    <!-- Indicators -->
                    <div class="flex justify-center gap-2 mt-6">
                        <template x-for="slide in slides">
                            <button @click="currentSlide = slide" class="w-3 h-3 rounded-full transition-colors" x-bind:class="currentSlide === slide ? 'bg-primary' : 'bg-outline-variant'"></button>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- 10. FAQ -->
        <section class="py-16 md:py-24 bg-surface-container-lowest" id="faq">
            <div class="max-w-3xl mx-auto px-margin_mobile md:px-margin_desktop">
                <div class="text-center mb-12">
                    <h2 class="font-headline text-headline-lg text-primary font-bold">Pertanyaan Umum (FAQ)</h2>
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
