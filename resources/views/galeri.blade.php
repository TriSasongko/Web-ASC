<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Galeri Kegiatan - Antasena Swimming Club</title>

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
            .masonry-grid {
                column-count: 1;
                column-gap: 1.5rem;
            }
            @media (min-width: 768px) {
                .masonry-grid { column-count: 2; }
            }
            @media (min-width: 1024px) {
                .masonry-grid { column-count: 3; }
            }
            .masonry-item {
                break-inside: avoid;
                margin-bottom: 1.5rem;
            }
            html {
                scroll-behavior: smooth;
            }
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="bg-background text-on-surface font-body text-body-md antialiased min-h-screen flex flex-col pt-24">

        <!-- Navbar -->
        @include('partials.header')

        <!-- Main Content -->
        <main class="flex-grow pb-16 md:pb-24" x-data="{ filter: 'Semua' }">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <header class="py-12 md:py-16 text-center">
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-6">Galeri Kegiatan</h1>
                    <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">
                        Momen-momen berharga dari latihan rutin, kompetisi, dan keceriaan siswa-siswi Antasena Swimming Club.
                    </p>
                </header>

                <!-- Gallery Filter -->
                <div class="flex flex-wrap justify-center gap-3 mb-12">
                    <button @click="filter = 'Semua'"
                        class="px-6 py-1.5 rounded-full font-body text-label-md transition-colors"
                        x-bind:class="filter === 'Semua' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'">
                        Semua
                    </button>
                    <button @click="filter = 'Latihan'"
                        class="px-6 py-1.5 rounded-full font-body text-label-md transition-colors"
                        x-bind:class="filter === 'Latihan' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'">
                        Latihan
                    </button>
                    <button @click="filter = 'Kejuaraan'"
                        class="px-6 py-1.5 rounded-full font-body text-label-md transition-colors"
                        x-bind:class="filter === 'Kejuaraan' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'">
                        Kejuaraan
                    </button>
                    <button @click="filter = 'Keceriaan'"
                        class="px-6 py-1.5 rounded-full font-body text-label-md transition-colors"
                        x-bind:class="filter === 'Keceriaan' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'">
                        Keceriaan
                    </button>
                </div>

                <!-- Masonry Gallery -->
                <div class="masonry-grid">
                    <!-- Gallery Item 1: Latihan -->
                    <div class="masonry-item pool-shadow bg-surface rounded-xl overflow-hidden group cursor-pointer border border-surface-variant transition-transform hover:-translate-y-1 duration-300" x-show="filter === 'Semua' || filter === 'Latihan'">
                        <div class="relative overflow-hidden aspect-[4/3]">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 alt="Kelas pemula belajar renang"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuCUcNvSxFzh0EKgSmjoo3O6vdZAoTq-2ZwUV3_Lo3tBtnUziRD7zRR8ItBch5z5BqPC3BxKLUFAVmWVyXrO5bF86DPThQnuijVZ3gY02Nb4QXRPRQqWP5hpLzLq0UjFfZEIqVS5j6N156xrxWbQ_JnBNBELEquo-l2Qvr9rRZZBD5y0MEf8aVKM_sPZcsw9wXc3yqWLH6J1jJgeVEljjSiY9qxeHkHroOIqKsWAg60ig4SZAmc5z6-l">
                        </div>
                        <div class="p-6 bg-surface">
                            <span class="inline-block px-3 py-0.5 bg-tertiary-fixed text-primary font-body text-label-sm rounded-full mb-3">Latihan</span>
                            <h3 class="font-headline text-headline-sm text-on-surface mb-2">Kelas Pemula Minggu Pagi</h3>
                            <p class="font-body text-body-md text-on-surface-variant text-sm">Fokus pada teknik pernapasan dasar dan kenyamanan di dalam air.</p>
                        </div>
                    </div>
                    <!-- Gallery Item 2: Kejuaraan -->
                    <div class="masonry-item pool-shadow bg-surface rounded-xl overflow-hidden group cursor-pointer border border-surface-variant transition-transform hover:-translate-y-1 duration-300" x-show="filter === 'Semua' || filter === 'Kejuaraan'">
                        <div class="relative overflow-hidden aspect-[3/4]">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 alt="Atlet bertanding gaya kupu-kupu"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZYP9DrL2p6CDEddW13ROaFkY8ujinkz0tPE7bQsp3098O6yAbvIMw8JISMZCiEL1_B2ZWfZeJQv0h_PCkgzASN8ie7PhWzfggt6PhiBBjZUNCVs9JGEK7YgmgwetbGAEA4DuM6jpbihFRB870XTtgEOFAFG_N0yicTFKz9T-HQYMljZL_b_kuxGXa-6SNJmtv-Qflax9BHuhJJO70V0M0lf8--4VTLF01VJGU4SBtCtfFuSJVR26I">
                        </div>
                        <div class="p-6 bg-surface">
                            <span class="inline-block px-3 py-0.5 bg-orange-lighter text-orange-dark font-body text-label-sm rounded-full mb-3">Kejuaraan</span>
                            <h3 class="font-headline text-headline-sm text-on-surface mb-2">Kejuaraan Renang Antar Klub 2024</h3>
                            <p class="font-body text-body-md text-on-surface-variant text-sm">Atlet elit kami menunjukkan performa terbaik di gaya kupu-kupu.</p>
                        </div>
                    </div>
                    <!-- Gallery Item 3: Keceriaan -->
                    <div class="masonry-item pool-shadow bg-surface rounded-xl overflow-hidden group cursor-pointer border border-surface-variant transition-transform hover:-translate-y-1 duration-300" x-show="filter === 'Semua' || filter === 'Keceriaan'">
                        <div class="relative overflow-hidden aspect-square">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 alt="Siswa bermain setelah latihan"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuAjTXnqdAtnvb9bjHFFIHJj9zRpvN4sB5CZ6a5E4tAnDZ9ieHGzYEFNuXSDm-hzBY0L_NPQoNDgTiexhs7d9kNwHekjMkcpwM7KvnZD-TjwB6lG7IwS7OpzD-lj1zLBC8UNLp0vdsfgGDvhcs8DoC4B9JuGQjNcqdXjOrHz35WYjZuK3mewBwydGDlIP6Dbg6i5_g2T9ggbhtItEaCCiY7Xdbe7p0uTWuWwcZ2iLliTXue6hXMx3_jU">
                        </div>
                        <div class="p-6 bg-surface">
                            <span class="inline-block px-3 py-0.5 bg-primary-fixed text-primary font-body text-label-sm rounded-full mb-3">Keceriaan</span>
                            <h3 class="font-headline text-headline-sm text-on-surface mb-2">Sesi Bermain Setelah Latihan</h3>
                            <p class="font-body text-body-md text-on-surface-variant text-sm">Membangun keakraban antar siswa setelah sesi latihan yang intens.</p>
                        </div>
                    </div>
                    <!-- Gallery Item 4: Video -->
                    <div class="masonry-item pool-shadow bg-surface rounded-xl overflow-hidden group cursor-pointer border border-surface-variant transition-transform hover:-translate-y-1 duration-300" x-show="filter === 'Semua' || filter === 'Latihan'">
                        <div class="relative overflow-hidden aspect-video">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 alt="Suasana fasilitas kolam renang"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuBMp6VdrjP2owj06zwgIsl1PeQX6f8BnsLBZc8-3XPGHo1MxuwGvj_m6bs4-b3BwDjTY8WeG7ra8Ga_f530QlNv5bDeNXbdOzW6L-0jvhvWL44sUvz0dskUOzVzAltCBKFuavg81mS1dV4XGjt_hveZT-Rf0Zq7UoDwk1YkW-tHLDDKk0yp7SU310vXLcq20CiblRFQhsw68mpHu-yzHMZGBlpjdx2xrNW66MhEO3s7rAobUwwQNo6q">
                            <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-colors">
                                <span class="material-symbols-outlined text-white text-[48px] drop-shadow-md">play_circle</span>
                            </div>
                        </div>
                        <div class="p-6 bg-surface">
                            <span class="inline-block px-3 py-0.5 bg-tertiary-fixed text-primary font-body text-label-sm rounded-full mb-3">Video Latihan</span>
                            <h3 class="font-headline text-headline-sm text-on-surface mb-2">Drill Teknik Gaya Bebas</h3>
                            <p class="font-body text-body-md text-on-surface-variant text-sm">Fokus perbaikan rotasi tubuh untuk efisiensi gaya bebas.</p>
                        </div>
                    </div>
                    <!-- Gallery Item 5: Latihan -->
                    <div class="masonry-item pool-shadow bg-surface rounded-xl overflow-hidden group cursor-pointer border border-surface-variant transition-transform hover:-translate-y-1 duration-300" x-show="filter === 'Semua' || filter === 'Latihan'">
                        <div class="relative overflow-hidden aspect-[4/5]">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 alt="Evaluasi individu bersama coach"
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzeQrFWUiVySl2r_fHSA50I1bjLEwtTpJP8fOfhW5FLua3WIAkbhu5qxzpxt-MlXvt6w6_AU66G0yS4RQpcBVMX49LiSndFe_qBy6cpR37T-ILfwOYdv-WfYGc7WOSTYVG0XhK2ovyPecIkZk9IshriB_YQuwz4zFFrXIcagwP8w8A-5xXHoOgHpxZNzScefuHawPs7HDbxXzDKHRdCZymwsRS7suHYe0frULgH4xqfKE1x-zxeZ8g">
                        </div>
                        <div class="p-6 bg-surface">
                            <span class="inline-block px-3 py-0.5 bg-tertiary-fixed text-primary font-body text-label-sm rounded-full mb-3">Latihan</span>
                            <h3 class="font-headline text-headline-sm text-on-surface mb-2">Evaluasi Individu bersama Coach</h3>
                            <p class="font-body text-body-md text-on-surface-variant text-sm">Setiap siswa mendapatkan perhatian khusus untuk perkembangan optimal.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <a href="/#galeri"
                        class="inline-block font-body text-label-md px-8 py-2 rounded-lg border-2 border-primary text-primary hover:bg-primary-container/10 transition-colors duration-300">
                        Muat Lebih Banyak
                    </a>
                </div>
            </div>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
