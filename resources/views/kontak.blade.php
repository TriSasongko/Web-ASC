<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hubungi Kami - Antasena Swimming Club</title>

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
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-6">Hubungi Kami</h1>
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
                            <div>
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Alamat</h2>
                                <p class="text-on-surface-variant">{{ \App\Models\User::adminAddress() }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-6 rounded-2xl border border-outline-variant/50 bg-surface pool-shadow">
                            <span class="w-12 h-12 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">call</span>
                            </span>
                            <div>
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Telepon / WhatsApp</h2>
                                <p class="text-on-surface-variant">
                                    <a href="{{ \App\Models\User::adminTelLink() }}" class="hover:text-orange transition-colors">{{ \App\Models\User::adminWaDisplay() }}</a>
                                </p>
                                <p class="text-on-surface-variant">
                                    <a href="{{ \App\Models\User::adminWaLink() }}" target="_blank" class="hover:text-orange transition-colors">Chat via WhatsApp</a>
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-6 rounded-2xl border border-outline-variant/50 bg-surface pool-shadow">
                            <span class="w-12 h-12 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">mail</span>
                            </span>
                            <div>
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Email</h2>
                                <p class="text-on-surface-variant">
                                    <a href="mailto:gilangaudiokorgiepangestu@gmail.com" class="hover:text-orange transition-colors">gilangaudiokorgiepangestu@gmail.com</a>
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-6 rounded-2xl border border-outline-variant/50 bg-surface pool-shadow">
                            <span class="w-12 h-12 shrink-0 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">schedule</span>
                            </span>
                            <div>
                                <h2 class="font-headline text-headline-sm font-bold text-primary mb-2">Jam Operasional</h2>
                                <p class="text-on-surface-variant">Senin – Jumat: 08.00 – 20.00</p>
                                <p class="text-on-surface-variant">Sabtu – Minggu: 07.00 – 18.00</p>
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
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.3473943868075!2d105.23627687474358!3d-5.363862494614921!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40c54bbc12a533%3A0xf38f052a38ab7537!2sKolam%20Renang%20Universitas%20Lampung!5e0!3m2!1sid!2sid!4v1786456619529!5m2!1sid!2sid"
                            class="w-full h-[300px] md:h-[420px] border-0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            title="Lokasi Antasena Swimming Club"></iframe>
                        <div class="absolute bottom-4 left-4 bg-surface/95 backdrop-blur px-5 py-3 rounded-2xl pool-shadow flex items-center gap-3 pointer-events-none">
                            <span class="w-10 h-10 rounded-full bg-primary-container text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined">map</span>
                            </span>
                            <div>
                                <p class="font-headline text-headline-sm font-bold text-primary">Kolam Renang Universitas Lampung</p>
                                <p class="text-on-surface-variant text-body-sm">Bandar Lampung, Lampung</p>
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
