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
            <section class="text-center mb-12">
                <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-4">Program Pelatihan Kami</h1>
                <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">Pilih program yang paling sesuai dengan kebutuhan dan target kemampuan renang Anda atau anak Anda. Kami menyediakan lingkungan belajar yang aman dan terstruktur.</p>
            </section>

            <!-- Programs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Program 1: Private -->
                <div class="bg-surface rounded-xl p-6 pool-shadow border border-outline/10 flex flex-col hover:-translate-y-1 transition-transform duration-300">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4 text-primary">
                            <span class="material-symbols-outlined filled" data-icon="school">school</span>
                        </div>
                        <h2 class="font-headline text-headline-sm text-primary mb-2">Private</h2>
                        <p class="font-headline text-headline-md text-orange mb-4">Rp500.000<span class="font-body text-body-md text-on-surface-variant"> / paket</span></p>
                    </div>
                    <div class="flex-grow">
                        <ul class="space-y-3 mb-6 text-on-surface-variant">
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>Maksimal 3 Anak per sesi</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>8 Pertemuan intensif</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>Fokus pada teknik dasar &amp; keselamatan</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                        class="w-full text-center bg-orange text-white py-3 rounded-lg font-body text-label-md hover:bg-orange-light transition-colors mt-auto">
                        Daftar Sekarang
                    </a>
                </div>

                <!-- Program 2: Mini Private -->
                <div class="bg-surface rounded-xl p-6 pool-shadow border border-outline/10 flex flex-col hover:-translate-y-1 transition-transform duration-300">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4 text-primary">
                            <span class="material-symbols-outlined filled" data-icon="person_play">person_play</span>
                        </div>
                        <h2 class="font-headline text-headline-sm text-primary mb-2">Mini Private</h2>
                        <p class="font-headline text-headline-md text-orange mb-4">Rp300.000<span class="font-body text-body-md text-on-surface-variant"> / paket</span></p>
                    </div>
                    <div class="flex-grow">
                        <ul class="space-y-3 mb-6 text-on-surface-variant">
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>Maksimal 3 Anak per sesi</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>4 Pertemuan pengenalan air</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>Jadwal lebih fleksibel</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                        class="w-full text-center border-2 border-primary text-primary py-3 rounded-lg font-body text-label-md hover:bg-primary hover:text-on-primary transition-colors mt-auto">
                        Daftar Sekarang
                    </a>
                </div>

                <!-- Program 3: Reguler -->
                <div class="bg-surface rounded-xl p-6 pool-shadow border border-outline/10 flex flex-col hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-orange text-white px-3 py-1 rounded-bl-lg font-body text-label-md text-[12px]">Populer</div>
                    <div class="mb-4 mt-2">
                        <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4 text-primary">
                            <span class="material-symbols-outlined filled" data-icon="groups">groups</span>
                        </div>
                        <h2 class="font-headline text-headline-sm text-primary mb-2">Reguler</h2>
                        <p class="font-headline text-headline-md text-orange mb-4">Rp350.000<span class="font-body text-body-md text-on-surface-variant"> / paket</span></p>
                    </div>
                    <div class="flex-grow">
                        <ul class="space-y-3 mb-6 text-on-surface-variant">
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>3 - 4 Anak per kelas</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>8 Pertemuan terstruktur</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>Pembelajaran kelompok interaktif</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                        class="w-full text-center bg-primary text-on-primary py-3 rounded-lg font-body text-label-md hover:bg-primary-container transition-colors mt-auto">
                        Daftar Sekarang
                    </a>
                </div>

                <!-- Program 4: Mini Reguler -->
                <div class="bg-surface rounded-xl p-6 pool-shadow border border-outline/10 flex flex-col hover:-translate-y-1 transition-transform duration-300">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4 text-primary">
                            <span class="material-symbols-outlined filled" data-icon="diversity_3">diversity_3</span>
                        </div>
                        <h2 class="font-headline text-headline-sm text-primary mb-2">Mini Reguler</h2>
                        <p class="font-headline text-headline-md text-orange mb-4">Rp200.000<span class="font-body text-body-md text-on-surface-variant"> / paket</span></p>
                    </div>
                    <div class="flex-grow">
                        <ul class="space-y-3 mb-6 text-on-surface-variant">
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>3 - 4 Anak per kelas</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>4 Pertemuan dasar</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                <span>Cocok untuk pemula</span>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                        class="w-full text-center border-2 border-primary text-primary py-3 rounded-lg font-body text-label-md hover:bg-primary hover:text-on-primary transition-colors mt-auto">
                        Daftar Sekarang
                    </a>
                </div>

                <!-- Program 5: Kompetitif -->
                <div class="bg-surface rounded-xl p-6 pool-shadow border border-outline/10 flex flex-col hover:-translate-y-1 transition-transform duration-300 md:col-span-2 lg:col-span-2">
                    <div class="flex flex-col md:flex-row gap-6 h-full">
                        <div class="md:w-1/2">
                            <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4 text-primary">
                                <span class="material-symbols-outlined filled" data-icon="emoji_events">emoji_events</span>
                            </div>
                            <h2 class="font-headline text-headline-sm text-primary mb-2">Kompetitif (Prestasi)</h2>
                            <p class="font-headline text-headline-md text-orange mb-4">Rp300.000<span class="font-body text-body-md text-on-surface-variant"> / bulan</span></p>
                            <p class="text-on-surface-variant mb-4">Program khusus untuk atlet muda yang ingin meraih prestasi di tingkat regional hingga nasional. Pelatihan intensif dengan standar kompetisi.</p>
                        </div>
                        <div class="md:w-1/2 flex flex-col">
                            <div class="flex-grow">
                                <ul class="space-y-3 mb-6 text-on-surface-variant">
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                        <span>Latihan fisik &amp; teknik lanjutan</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                        <span>Persiapan turnamen berkala</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                        <span>Evaluasi performa atlet</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="material-symbols-outlined text-primary text-[20px] filled">check_circle</span>
                                        <span>Jadwal latihan rutin intensif</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('register') }}"
                                class="w-full text-center bg-primary text-on-primary py-3 rounded-lg font-body text-label-md hover:bg-primary-container transition-colors mt-auto">
                                Daftar Seleksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
