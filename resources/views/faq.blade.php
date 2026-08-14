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
                <!-- Hero -->
                <section class="relative text-center pt-8 md:pt-12 pb-10 md:pb-12 overflow-hidden">
                    <div class="absolute inset-0 -z-10 pointer-events-none">
                        <div class="absolute -top-20 -right-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
                        <div class="absolute top-6 -left-24 w-80 h-80 bg-orange/10 rounded-full blur-3xl"></div>
                    </div>
                    <span class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full font-body text-label-md font-semibold mb-5">
                        <span class="material-symbols-outlined text-[18px]">help</span>
                        Pusat Bantuan
                    </span>
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-4">Pertanyaan Umum (FAQ)</h1>
                    <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">
                        Temukan jawaban atas berbagai pertanyaan yang sering diajukan seputar pendaftaran, kelas, dan pembayaran di Antasena Swimming Club.
                    </p>
                </section>

                <div class="max-w-3xl mx-auto space-y-4">
                    @php
                        $faqs = [
                            ['q' => 'Bagaimana cara mendaftar di ASC?', 'a' => 'Pendaftaran dapat dilakukan secara online dengan mengklik tombol "Daftar" di website ini, atau Anda bisa datang langsung ke meja pendaftaran kami di Kolam Renang Universitas Lampung pada jam operasional.'],
                            ['q' => 'Mulai usia berapa anak bisa ikut kelas renang?', 'a' => 'Kami menerima siswa mulai dari usia 4 tahun untuk program reguler/mini reguler anak-anak. Untuk usia di bawah 4 tahun, disarankan mengikuti program private dengan pendampingan khusus.'],
                            ['q' => 'Bagaimana sistem pembayaran biayanya?', 'a' => 'Pembayaran dapat dilakukan melalui transfer bank, e-wallet (GoPay, OVO, Dana), atau secara tunai di lokasi pendaftaran. Pembayaran dilakukan di awal sebelum sesi pertama dimulai.'],
                            ['q' => 'Apakah biaya sudah termasuk tiket masuk kolam?', 'a' => 'Ya, seluruh biaya program kelas (Private, Reguler, dll) yang tercantum sudah termasuk biaya tiket masuk kolam renang untuk siswa selama sesi latihan berlangsung. Pendamping/orang tua yang masuk area kolam namun tidak berenang mungkin dikenakan tarif masuk reguler kolam renang (bukan dari pihak ASC).'],
                            ['q' => 'Apakah tersedia kelas untuk dewasa?', 'a' => 'Ya, tersedia. Dewasa dapat mengikuti program private, mini private, atau reguler dengan kelompok dewasa. Program dapat disesuaikan mulai dari pemula total hingga perbaikan teknik gaya.'],
                            ['q' => 'Bagaimana saya bisa memantau perkembangan anak?', 'a' => 'ASC menyediakan E-Raport digital yang bisa diakses secara berkala. Di dalamnya tercatat kehadiran, penilaian perkembangan teknik, serta catatan evaluasi dari coach untuk setiap siswa.'],
                        ];
                    @endphp

                    @foreach ($faqs as $i => $faq)
                        <div class="border border-outline-variant/50 rounded-2xl overflow-hidden pool-shadow bg-surface" x-data="{ expanded: false }">
                            <button @click="expanded = !expanded" :aria-expanded="expanded ? 'true' : 'false'"
                                class="flex justify-between items-center gap-4 w-full p-5 md:p-6 text-left hover:bg-surface-container-low transition-colors group">
                                <span class="flex items-start gap-3 min-w-0">
                                    <span class="shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-headline text-label-md font-bold">
                                        {{ $i + 1 }}
                                    </span>
                                    <span class="font-headline text-headline-sm md:text-headline-md text-primary font-bold leading-snug">{{ $faq['q'] }}</span>
                                </span>
                                <span class="shrink-0 w-8 h-8 rounded-full border border-outline-variant/50 flex items-center justify-center text-on-surface-variant transition-transform duration-300 group-hover:bg-primary/10"
                                      x-bind:class="expanded ? 'rotate-180 bg-primary text-on-primary border-primary' : ''">
                                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                                </span>
                            </button>
                            <div class="overflow-hidden transition-[max-height] duration-300 ease-in-out"
                                 x-bind:style="expanded ? 'max-height: ' + $refs.body.scrollHeight + 'px' : 'max-height: 0px'">
                                <div x-ref="body" class="px-5 pb-5 md:px-6 md:pb-6">
                                    <div class="border-l-4 border-orange pl-4 md:pl-5 text-on-surface-variant text-body-md md:text-body-lg leading-relaxed">
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- CTA -->
                <div class="max-w-3xl mx-auto mt-14 md:mt-16">
                    <div class="bg-primary rounded-3xl p-8 md:p-12 text-center text-on-primary shadow-xl shadow-primary/20 relative overflow-hidden">
                        <div class="absolute -top-16 -left-16 w-64 h-64 bg-orange/20 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-20 -right-10 w-72 h-72 bg-surface/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <h2 class="font-headline text-headline-lg-mobile md:text-headline-xl font-bold mb-3">Masih Ada Pertanyaan?</h2>
                            <p class="font-body text-body-md md:text-body-lg max-w-xl mx-auto mb-8 text-on-primary/90">Tim kami siap membantu Anda. Hubungi kami melalui WhatsApp, email, atau kunjungi langsung meja pendaftaran kami.</p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ url('/kontak') }}"
                                    class="inline-flex items-center justify-center gap-2 bg-orange text-white px-8 py-4 rounded-xl font-body text-label-md hover:bg-orange-light transition-colors shadow-lg shadow-orange/40">
                                    <span class="material-symbols-outlined text-[18px]">mail</span>
                                    Hubungi Kami
                                </a>
                                <a href="{{ \App\Models\User::adminWaLink() }}" target="_blank"
                                    class="inline-flex items-center justify-center gap-2 bg-surface/10 backdrop-blur-sm border-2 border-on-primary text-on-primary px-8 py-4 rounded-xl font-body text-label-md hover:bg-surface/20 transition-colors">
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
