<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hubungi Kami - Antasena Swimming Club</title>
        <meta name="description" content="Hubungi Antasena Swimming Club untuk informasi pendaftaran, jadwal latihan, dan pertanyaan lainnya. Kami siap membantu Anda.">
        <meta property="og:title" content="Hubungi Kami - Antasena Swimming Club">
        <meta property="og:description" content="Hubungi Antasena Swimming Club untuk informasi pendaftaran, jadwal latihan, dan pertanyaan lainnya. Kami siap membantu Anda.">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ asset('images/Logo_ASR.png') }}">
        <meta property="og:url" content="{{ url('/kontak') }}">
        <link rel="canonical" href="{{ url('/kontak') }}">
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
                <header class="relative text-center pt-8 md:pt-12 pb-10 md:pb-12 overflow-hidden">
                    <div class="absolute inset-0 -z-10 pointer-events-none">
                        <div class="absolute -top-20 -right-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
                        <div class="absolute top-6 -left-24 w-80 h-80 bg-orange/10 rounded-full blur-3xl"></div>
                    </div>
                    <span class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full font-body text-label-md font-semibold mb-5">
                        <span class="material-symbols-outlined text-[18px]">support_agent</span>
                        Kami Siap Membantu
                    </span>
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-4">Hubungi Kami</h1>
                    <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">
                        Ada pertanyaan seputar pendaftaran, jadwal, atau program? Silakan hubungi kami melalui saluran di bawah ini.
                    </p>
                </header>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                    <!-- Contact Info -->
                    <div class="space-y-6">
                        <div class="flex gap-4 p-6 rounded-2xl border border-outline-variant/50 bg-surface pool-shadow">
                            <span class="w-12 h-12 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">location_on</span>
                            </span>
                            <div class="min-w-0">
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Alamat</h2>
                                <p class="text-on-surface-variant break-words">{{ \App\Models\User::adminAddress() }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-6 rounded-2xl border border-outline-variant/50 bg-surface pool-shadow">
                            <span class="w-12 h-12 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">call</span>
                            </span>
                            <div class="min-w-0">
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Telepon / WhatsApp</h2>
                                <p class="text-on-surface-variant break-words">
                                    <a href="{{ \App\Models\User::adminTelLink() }}" class="hover:text-orange transition-colors">{{ \App\Models\User::adminWaDisplay() }}</a>
                                </p>
                                <p class="text-on-surface-variant break-words">
                                    <a href="{{ \App\Models\User::adminWaLink() }}" target="_blank" class="hover:text-orange transition-colors">Chat via WhatsApp</a>
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-6 rounded-2xl border border-outline-variant/50 bg-surface pool-shadow">
                            <span class="w-12 h-12 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">mail</span>
                            </span>
                            <div class="min-w-0">
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Email</h2>
                                <p class="text-on-surface-variant break-all">
                                    <a href="mailto:{{ $settings['kontak_email'] }}" class="hover:text-orange transition-colors">{{ $settings['kontak_email'] }}</a>
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-6 rounded-2xl border border-outline-variant/50 bg-surface pool-shadow">
                            <span class="w-12 h-12 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">schedule</span>
                            </span>
                            <div class="min-w-0">
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Jam Operasional</h2>
                                <p class="text-on-surface-variant break-words">{{ $settings['kontak_hours_weekday'] }}</p>
                                <p class="text-on-surface-variant break-words">{{ $settings['kontak_hours_weekend'] }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-6 rounded-2xl border border-outline-variant/50 bg-surface pool-shadow">
                            <span class="w-12 h-12 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">alternate_email</span>
                            </span>
                            <div class="min-w-0">
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Instagram</h2>
                                <p class="text-on-surface-variant break-words">
                                    <a href="{{ $settings['kontak_instagram'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-orange transition-colors">{{ $settings['kontak_instagram_handle'] }}</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="rounded-2xl border border-outline-variant/50 bg-surface pool-shadow p-8 md:p-10"
                         x-data="{ nama: '', telepon: '', pesan: '' }">
                        <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg font-bold text-primary mb-2">Kirim Pesan</h2>
                        <p class="text-on-surface-variant mb-8">
                            Isi formulir berikut, pesan Anda akan diteruskan melalui WhatsApp kepada tim kami.
                        </p>
                        <form
                            class="space-y-5"
                            @submit.prevent="window.open('{{ \App\Models\User::adminWaLink() }}?text=' + encodeURIComponent('Halo Antasena Swimming Club, saya ' + nama + (telepon ? ' (' + telepon + ')' : '') + '. ' + pesan), '_blank')">
                            <div>
                                <label class="block font-body text-label-md text-primary mb-2" for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" x-model="nama" required
                                    class="w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    placeholder="Nama Anda">
                            </div>
                            <div>
                                <label class="block font-body text-label-md text-primary mb-2" for="telepon">No. WhatsApp</label>
                                <input type="tel" id="telepon" x-model="telepon"
                                    class="w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    placeholder="08xxxxxxx">
                            </div>
                            <div>
                                <label class="block font-body text-label-md text-primary mb-2" for="pesan">Pesan</label>
                                <textarea id="pesan" x-model="pesan" rows="5" required
                                    class="w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 resize-none"
                                    placeholder="Tulis pertanyaan atau pesan Anda..."></textarea>
                            </div>
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-orange text-white px-8 py-4 rounded-xl font-body text-label-md hover:bg-orange-light transition-colors shadow-lg shadow-orange/40 active:scale-95">
                                <span class="material-symbols-outlined text-[20px]">send</span>
                                Kirim via WhatsApp
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Map -->
                <div class="mt-16">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                        <iframe
                            src="{{ $settings['kontak_maps_url'] }}"
                            class="w-full h-[300px] md:h-[420px] border-0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            title="Lokasi Antasena Swimming Club"></iframe>
                        <div class="absolute bottom-3 left-3 right-3 sm:bottom-4 sm:left-4 sm:right-auto bg-surface/95 backdrop-blur px-4 sm:px-5 py-3 rounded-2xl pool-shadow flex items-center gap-3 pointer-events-none">
                            <span class="w-10 h-10 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">map</span>
                            </span>
                            <div class="min-w-0">
                                <p class="font-headline text-headline-sm font-bold text-primary truncate">Kolam Renang Universitas Lampung</p>
                                <p class="text-on-surface-variant text-body-sm truncate">Bandar Lampung, Lampung</p>
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
