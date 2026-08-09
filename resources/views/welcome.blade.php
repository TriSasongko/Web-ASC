<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'ASC Academy') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-surface">
        <!-- Top Bar -->
        <header class="sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-primary-container text-on-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">sports_soccer</span>
                    </span>
                    <span class="font-headline text-headline-sm text-on-surface">ASC Academy</span>
                </a>

                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                            <span class="material-symbols-outlined text-[18px]">dashboard</span>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/5 rounded-full"></div>
            <div class="absolute top-40 -left-24 w-72 h-72 bg-tertiary-fixed/30 rounded-full"></div>
            <div class="relative max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop py-16 md:py-24">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 bg-[#E6F8FC] text-secondary font-label-sm text-label-sm px-3 py-1 rounded-full mb-6">
                        <span class="material-symbols-outlined text-[16px]">celebration</span>
                        Sekolah Sepak Bola #1 di Wilayah Anda
                    </span>
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-on-surface leading-tight">
                        Wujudkan Mimpi Putra-Putri Anda Jadi Pesepak Bola Profesional
                    </h1>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-5 leading-relaxed">
                        ASC Academy menyediakan program latihan sepak bola terstruktur dengan pelatih berlisensi,
                        pemantauan perkembangan berkala, serta rapor digital (E-Raport) yang transparan untuk orang tua.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 mt-8">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-6 py-3 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-6 py-3 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                                <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                                Daftarkan Anak Sekarang
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-6 py-3 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                                Sudah Punya Akun? Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop py-12 md:py-16">
            <div class="text-center mb-10">
                <h2 class="font-headline text-headline-lg text-on-surface">Kenapa Memilih ASC Academy?</h2>
                <p class="font-body-sm text-body-sm text-outline mt-2">Program latihan yang terstruktur dan terukur untuk setiap usia.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
                    <div class="p-2.5 bg-[#E6F8FC] text-secondary rounded-lg w-fit mb-4">
                        <span class="material-symbols-outlined">calendar_month</span>
                    </div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Jadwal Teratur</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">Latihan rutin dengan jadwal terjadwal dan komunikasi yang jelas.</p>
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
                    <div class="p-2.5 bg-tertiary-fixed text-on-tertiary-fixed rounded-lg w-fit mb-4">
                        <span class="material-symbols-outlined">sports_soccer</span>
                    </div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Pelatih Berlisensi</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">Dibimbing pelatih berpengalaman dengan sertifikasi kepelatihan.</p>
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
                    <div class="p-2.5 bg-[#E8F5E9] text-[#2E7D32] rounded-lg w-fit mb-4">
                        <span class="material-symbols-outlined">monitoring</span>
                    </div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Evaluasi Berkala</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">Perkembangan anak dinilai setiap sesi latihan oleh pelatih.</p>
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
                    <div class="p-2.5 bg-[#FFF3E0] text-[#E65100] rounded-lg w-fit mb-4">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <h3 class="font-headline text-headline-sm text-on-surface">E-Raport Digital</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">Orang tua bisa memantau rapor &amp; kehadiran anak secara online.</p>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop pb-16">
            <div class="bg-primary rounded-2xl p-8 md:p-12 text-center relative overflow-hidden">
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-white/10 rounded-full"></div>
                <div class="relative">
                    <h2 class="font-headline text-headline-lg text-on-primary">Siap Menjadi Bagian dari Keluarga ASC?</h2>
                    <p class="font-body-sm text-body-sm text-on-primary/80 mt-2 max-w-xl mx-auto">Daftarkan putra-putri Anda sekarang dan mulai perjalanan sepak bolanya bersama ASC Academy.</p>
                    <div class="flex justify-center gap-4 mt-8">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-flex items-center justify-center gap-2 bg-white text-primary px-6 py-3 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center gap-2 bg-white text-primary px-6 py-3 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                                Daftar Sekarang
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <footer class="border-t border-outline-variant/30 py-8">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary-container text-on-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">sports_soccer</span>
                    </span>
                    <span class="font-headline text-headline-sm text-on-surface">ASC Academy</span>
                </div>
                <p class="font-body-sm text-body-sm text-outline">&copy; {{ date('Y') }} {{ config('app.name', 'ASC Academy') }}. Semua hak dilindungi.</p>
            </div>
        </footer>
    </body>
</html>
