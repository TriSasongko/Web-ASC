<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pertanyaan Umum (FAQ) - Antasena Swimming Club</title>
        <meta name="description" content="Jawaban atas pertanyaan umum seputar pendaftaran, jadwal latihan, biaya, dan program di Antasena Swimming Club.">
        <meta property="og:title" content="Pertanyaan Umum (FAQ) - Antasena Swimming Club">
        <meta property="og:description" content="Jawaban atas pertanyaan umum seputar pendaftaran, jadwal latihan, biaya, dan program di Antasena Swimming Club.">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ asset('images/Logo_ASR.png') }}">
        <meta property="og:url" content="{{ url('/faq') }}">
        <link rel="canonical" href="{{ url('/faq') }}">
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
            html {
                scroll-behavior: smooth;
            }
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="flex flex-col min-h-screen pt-24 antialiased bg-background text-on-background font-body text-body-md">

        <!-- Navbar -->
        @include('partials.header')

        <!-- Main Content -->
        <main class="flex-grow pb-16 md:pb-24">
            <div class="mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
                <!-- Hero -->
                <section class="relative pt-8 pb-10 overflow-hidden text-center md:pt-12 md:pb-12">
                    <div class="absolute inset-0 pointer-events-none -z-10">
                        <div class="absolute rounded-full -top-20 -right-20 w-72 h-72 bg-primary/10 blur-3xl"></div>
                        <div class="absolute rounded-full top-6 -left-24 w-80 h-80 bg-orange/10 blur-3xl"></div>
                    </div>
                    <span class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full font-body text-label-md font-semibold mb-5">
                        <span class="material-symbols-outlined text-[18px]">help</span>
                        Pusat Bantuan
                    </span>
                    <h1 class="mb-4 font-headline text-headline-lg-mobile md:text-headline-xl text-primary">Pertanyaan Umum (FAQ)</h1>
                    <p class="max-w-2xl mx-auto font-body text-body-lg text-on-surface-variant">
                        Temukan jawaban atas berbagai pertanyaan yang sering diajukan seputar pendaftaran, kelas, dan pembayaran di Antasena Swimming Club.
                    </p>
                </section>

                <div class="max-w-3xl mx-auto space-y-4">
                    @php
                        $faqs = [
                            ['q' => 'Bagaimana cara mendaftar di ASC?', 'a' => 'Untuk mendaftar, Anda cukup masuk ke website dan menghubungi admin kami yang akan membantu seluruh proses pendaftarannya.'],
                            ['q' => 'Mulai usia berapa anak bisa ikut kelas renang?', 'a' => 'Kami menerima siswa mulai dari usia 3 tahun untuk program reguler/mini reguler anak-anak. Untuk usia di bawah 3 tahun, disarankan mengikuti program private dengan pendampingan khusus.'],
                            ['q' => 'Bagaimana sistem pembayaran biayanya?', 'a' => 'Pembayaran dapat dilakukan melalui transfer bank, e-wallet (GoPay, OVO, Dana), atau secara tunai di lokasi pendaftaran. Pembayaran dilakukan di awal sebelum sesi pertama dimulai.'],
                            ['q' => 'Apakah biaya sudah termasuk tiket masuk kolam?', 'a' => 'Tidak, biaya paket belum termasuk tiket masuk. Biaya yang tertera hanya untuk program latihan bersama pelatih. Tiket masuk kolam renang dibeli terpisah di lokasi (loket kolam) baik untuk siswa yang akan latihan maupun orang tua/pendamping yang ikut masuk ke area kolam.'],
                            ['q' => 'Apakah tersedia kelas untuk dewasa?', 'a' => 'Ya, tersedia. Dewasa dapat mengikuti program private, mini private, atau reguler dengan kelompok dewasa. Program dapat disesuaikan mulai dari pemula total hingga perbaikan teknik gaya.'],
                            ['q' => 'Bagaimana saya bisa memantau perkembangan anak?', 'a' => 'ASC menyediakan E-Raport digital yang bisa diakses secara berkala. Di dalamnya tercatat kehadiran, penilaian perkembangan teknik, serta catatan evaluasi dari coach untuk setiap siswa.'],
                        ];
                    @endphp

                    @foreach ($faqs as $i => $faq)
                        <div class="overflow-hidden border border-outline-variant/50 rounded-2xl pool-shadow bg-surface" x-data="{ expanded: false }">
                            <button @click="expanded = !expanded" :aria-expanded="expanded ? 'true' : 'false'"
                                class="flex items-center justify-between w-full gap-4 p-5 text-left transition-colors md:p-6 hover:bg-surface-container-low group">
                                <span class="flex items-start min-w-0 gap-3">
                                    <span class="flex items-center justify-center w-8 h-8 font-bold rounded-full shrink-0 bg-primary/10 text-primary font-headline text-label-md">
                                        {{ $i + 1 }}
                                    </span>
                                    <span class="font-bold leading-snug font-headline text-headline-sm md:text-headline-md text-primary">{{ $faq['q'] }}</span>
                                </span>
                                <span class="flex items-center justify-center w-8 h-8 transition-transform duration-300 border rounded-full shrink-0 border-outline-variant/50 text-on-surface-variant group-hover:bg-primary/10"
                                      x-bind:class="expanded ? 'rotate-180 bg-primary text-on-primary border-primary' : ''">
                                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                                </span>
                            </button>
                            <div class="overflow-hidden transition-[max-height] duration-300 ease-in-out"
                                 x-bind:style="expanded ? 'max-height: ' + $refs.body.scrollHeight + 'px' : 'max-height: 0px'">
                                <div x-ref="body" class="px-5 pb-5 md:px-6 md:pb-6">
                                    <div class="pl-4 leading-relaxed border-l-4 border-orange md:pl-5 text-on-surface-variant text-body-md md:text-body-lg">
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- CTA -->
                <div class="max-w-3xl mx-auto mt-14 md:mt-16">
                    <div class="relative p-8 overflow-hidden text-center shadow-xl bg-primary rounded-3xl md:p-12 text-on-primary shadow-primary/20">
                        <div class="absolute w-64 h-64 rounded-full -top-16 -left-16 bg-orange/20 blur-3xl"></div>
                        <div class="absolute rounded-full -bottom-20 -right-10 w-72 h-72 bg-surface/10 blur-3xl"></div>
                        <div class="relative z-10">
                            <h2 class="mb-3 font-bold font-headline text-headline-lg-mobile md:text-headline-xl">Masih Ada Pertanyaan?</h2>
                            <p class="max-w-xl mx-auto mb-8 font-body text-body-md md:text-body-lg text-on-primary/90">Tim kami siap membantu Anda. Hubungi kami melalui WhatsApp, email, atau kunjungi langsung meja pendaftaran kami.</p>
                            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                                <a href="{{ url('/kontak') }}"
                                    class="inline-flex items-center justify-center gap-2 px-8 py-4 text-white transition-colors shadow-lg bg-orange rounded-xl font-body text-label-md hover:bg-orange-light shadow-orange/40">
                                    <span class="material-symbols-outlined text-[18px]">mail</span>
                                    Hubungi Kami
                                </a>
                                <a href="{{ \App\Models\User::adminWaLink() }}" target="_blank"
                                    class="inline-flex items-center justify-center gap-2 px-8 py-4 transition-colors border-2 bg-surface/10 backdrop-blur-sm border-on-primary text-on-primary rounded-xl font-body text-label-md hover:bg-surface/20">
                                    <span class="material-symbols-outlined text-[18px]">chat</span>
                                    Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
