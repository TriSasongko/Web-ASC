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
            <!-- Hero Section -->
            <section class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop py-16 md:py-24">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-6">Sejarah Antasena</h1>
                        <p class="font-body text-body-lg text-on-surface-variant mb-6">Berdiri sejak tahun 2010, Antasena Swimming Club lahir dari semangat untuk mengembangkan bakat renang lokal menjadi atlet nasional yang tangguh, terinspirasi oleh kekuatan dan ketangkasan tokoh pewayangan Antasena.</p>
                    </div>
                    <div class="rounded-xl overflow-hidden pool-shadow">
                        <img class="w-full h-[400px] object-cover"
                             alt="Perenang profesional saat latihan di kolam renang"
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuAnLybRUA4tT6c-irG99xVMh8Dn_725KXJzeXb7Ae3I4Ehgd0bxTA2VRjJnjW7l0wYBUtOXWJb98inwlbY4Im27yv-dj_KQ99kSYZZfr8JDZtGTeyUePgwpSBKO6qN9ApGjGZdVBc85HLmPb-fkiHNSi1jEHvuseUaHxjFCzbKhcUCvmHwqn_fZuVLhU0NQXy8J8wzsrZLST70SoE86PjdX23y2uNrM_03Ny_hPn4Euxm_mXpr__Ng-">
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
                            <p class="font-body text-body-md text-on-surface-variant">Menjadi klub renang terbaik dan paling profesional di tingkat nasional, yang tidak hanya mencetak atlet berprestasi tinggi tetapi juga menanamkan karakter disiplin, pantang menyerah, dan sportivitas.</p>
                        </div>
                        <div class="bg-surface p-6 rounded-xl pool-shadow border border-outline/10">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-symbols-outlined text-orange text-[32px]">flag</span>
                                <h2 class="font-headline text-headline-md text-primary">Misi</h2>
                            </div>
                            <ul class="list-disc list-inside font-body text-body-md text-on-surface-variant space-y-2">
                                <li>Menyediakan program pelatihan berstandar internasional.</li>
                                <li>Mengembangkan talenta muda melalui sistem pembinaan yang terstruktur.</li>
                                <li>Menciptakan lingkungan latihan yang aman, mendukung, dan kompetitif.</li>
                                <li>Berpartisipasi aktif dan berprestasi dalam kejuaraan renang lokal hingga internasional.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tim Pelatih Section -->
            <section class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop py-16 md:py-24">
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary text-center mb-12">Tim Pelatih Profesional Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Head Coach -->
                    <div class="bg-surface rounded-xl pool-shadow border border-outline/10 overflow-hidden flex flex-col">
                        <img class="w-full h-64 object-cover"
                             alt="Budi Santoso, Head Coach"
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuDJ8Z_hbbyyiw7w1d6n_uJHClujDqRqdGNG_8NRjmwfPQ_Y12SY6NhoQHggQHhNh9gCTfZXZqAI-Co3LY8nLVqn0xyUbIZVN1pWoYmZqAPxyeqPRX7QI8Pa5lLFPgfi73QEjXArZLlq_9sklxUVgPx3MqPi1zi9EOxjx_-kh_-n9nc6-LSM371ehqrGZEFTsgDaqF80HUUX3LWQcbsD9G8aHjW3sSkdczR8bV1jBulVbx-7AMIBlBy1">
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-headline text-headline-sm text-primary mb-1">Budi Santoso</h3>
                                <p class="font-body text-label-md text-orange mb-4">Head Coach</p>
                                <p class="font-body text-body-md text-on-surface-variant">Mantan atlet nasional dengan pengalaman melatih lebih dari 15 tahun. Spesialisasi dalam pembinaan atlet elit dan teknik gaya bebas.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Coach 2 -->
                    <div class="bg-surface rounded-xl pool-shadow border border-outline/10 overflow-hidden flex flex-col">
                        <img class="w-full h-64 object-cover"
                             alt="Siti Rahmawati, Senior Coach"
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjo2e0GFs-gzFwcwW5ApCnVokG4ZGf4Qmlnv0S6825fzaiEjFnmwpK0MpxOQy1XeJn_zAVt59wVEFTNAwH-ZOMM1lyGNswGyPdiJuDBR-OkEaPzsBXx9gp6YLY0EGnbSBXI95hba0ruFBLDJrXKV1a7Y_HaxgHUoyAvwbFtoGrq9p5yHYb3tGzjiNGJxZ1WdpASL4cT_n0oVNuCKCbyXdcnrZ3zF_fR73Yz0QtCcdkPwTaHJEP7lSq">
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-headline text-headline-sm text-primary mb-1">Siti Rahmawati</h3>
                                <p class="font-body text-label-md text-orange mb-4">Senior Coach</p>
                                <p class="font-body text-body-md text-on-surface-variant">Fokus pada pengembangan usia dini dan teknik dasar. Memiliki sertifikasi pelatihan internasional.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Coach 3 -->
                    <div class="bg-surface rounded-xl pool-shadow border border-outline/10 overflow-hidden flex flex-col">
                        <img class="w-full h-64 object-cover"
                             alt="Andi Kurniawan, Performance Coach"
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuAIjtFpu4HmHCGjFI2tXi-yXkKffctGVgV9ukATEkVy9RYB0uSdl5Tmvwtj9ph21avxv7pvA-1Sv98thsIPChSP8MgSWqHSBFgrdAKvQ9tNAMdNVeSwznBut0cbqR9KHY61szTlZX42wJb3fQFWTv-dpjBSPpoiJ3eUn2Pqi_46CKX9J7sE6Ac4WzhUtmtKvBBaOx2xchxdVbfAw8--2UUwq9IZUdg4gqG0xvFM61YMRImCTIhxjqdb">
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-headline text-headline-sm text-primary mb-1">Andi Kurniawan</h3>
                                <p class="font-body text-label-md text-orange mb-4">Performance Coach</p>
                                <p class="font-body text-body-md text-on-surface-variant">Ahli dalam analisis biomekanik renang dan persiapan fisik untuk kompetisi tingkat lanjut.</p>
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
