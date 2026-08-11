<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pertanyaan Umum (FAQ) - Antasena Swimming Club</title>

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
        <main class="flex-grow pb-16 md:pb-24">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <header class="py-12 md:py-16 text-center">
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-6">Pertanyaan Umum (FAQ)</h1>
                    <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">
                        Temukan jawaban atas berbagai pertanyaan yang sering diajukan seputar pendaftaran, kelas, dan pembayaran di Antasena Swimming Club.
                    </p>
                </header>

                <div class="max-w-3xl mx-auto space-y-4">
                    <!-- Item 1 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden pool-shadow bg-surface" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-5 text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Bagaimana cara mendaftar di ASC?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-5 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Pendaftaran dapat dilakukan secara online dengan mengklik tombol "Daftar" di website ini, atau Anda bisa datang langsung ke meja pendaftaran kami di Kolam Renang Universitas Lampung pada jam operasional.
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden pool-shadow bg-surface" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-5 text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Mulai usia berapa anak bisa ikut kelas renang?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-5 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Kami menerima siswa mulai dari usia 4 tahun untuk program reguler/mini reguler anak-anak. Untuk usia di bawah 4 tahun, disarankan mengikuti program private dengan pendampingan khusus.
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden pool-shadow bg-surface" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-5 text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Bagaimana sistem pembayaran biayanya?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-5 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Pembayaran dapat dilakukan melalui transfer bank, e-wallet (GoPay, OVO, Dana), atau secara tunai di lokasi pendaftaran. Pembayaran dilakukan di awal sebelum sesi pertama dimulai.
                        </div>
                    </div>
                    <!-- Item 4 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden pool-shadow bg-surface" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-5 text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Apakah biaya sudah termasuk tiket masuk kolam?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-5 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Ya, seluruh biaya program kelas (Private, Reguler, dll) yang tercantum sudah termasuk biaya tiket masuk kolam renang untuk siswa selama sesi latihan berlangsung. Pendamping/orang tua yang masuk area kolam namun tidak berenang mungkin dikenakan tarif masuk reguler kolam renang (bukan dari pihak ASC).
                        </div>
                    </div>
                    <!-- Item 5 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden pool-shadow bg-surface" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-5 text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Apakah tersedia kelas untuk dewasa?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-5 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            Ya, tersedia. Dewasa dapat mengikuti program private, mini private, atau reguler dengan kelompok dewasa. Program dapat disesuaikan mulai dari pemula total hingga perbaikan teknik gaya.
                        </div>
                    </div>
                    <!-- Item 6 -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden pool-shadow bg-surface" x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex justify-between items-center w-full p-5 text-left font-headline text-headline-sm text-primary hover:bg-surface-container-low transition-colors">
                            <span>Bagaimana saya bisa memantau perkembangan anak?</span>
                            <span class="material-symbols-outlined transition-transform duration-300" x-bind:class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div class="p-5 bg-surface border-t border-outline-variant/30 text-on-surface-variant" x-cloak x-show="expanded">
                            ASC menyediakan E-Raport digital yang bisa diakses secara berkala. Di dalamnya tercatat kehadiran, penilaian perkembangan teknik, serta catatan evaluasi dari coach untuk setiap siswa.
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="max-w-3xl mx-auto mt-16">
                    <div class="bg-primary rounded-3xl p-10 md:p-12 text-center text-on-primary shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-container rounded-full mix-blend-multiply filter blur-3xl opacity-70 transform translate-x-1/2 -translate-y-1/2"></div>
                        <div class="relative z-10">
                            <h2 class="font-headline text-headline-lg-mobile md:text-headline-xl font-bold mb-4">Masih Ada Pertanyaan?</h2>
                            <p class="font-body text-body-lg max-w-xl mx-auto mb-8 text-on-primary/90">Tim kami siap membantu Anda. Hubungi kami melalui WhatsApp, email, atau kunjungi langsung meja pendaftaran kami.</p>
                            <a href="{{ url('/kontak') }}"
                                class="inline-block bg-orange text-white px-8 py-4 rounded-xl font-body text-label-md hover:bg-orange-light transition-colors shadow-lg shadow-orange/40 hover:scale-105 transform duration-200">
                                Hubungi Kami
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
