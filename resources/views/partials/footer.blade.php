@php
    $footerEmail = \App\Models\LandingSetting::get('kontak_email');
    $footerInstagram = \App\Models\LandingSetting::get('kontak_instagram');
    $footerInstagramHandle = \App\Models\LandingSetting::get('kontak_instagram_handle', '@asc_lampung');
    $footerMapsUrl = \App\Models\LandingSetting::get('kontak_maps_url');
@endphp
<!-- Footer -->
<footer class="relative w-full mt-24 overflow-hidden border-t-4 bg-surface-container-highest text-on-surface font-body text-body-md rounded-t-xl border-tertiary-fixed" id="kontak">
    <!-- Water reflection effect -->
    <div class="absolute top-0 left-0 w-full h-32 opacity-50 pointer-events-none bg-gradient-to-b from-primary/5 to-transparent"></div>
    <div class="relative z-10 grid grid-cols-1 gap-12 py-16 mx-auto md:grid-cols-2 lg:grid-cols-4 px-margin_mobile md:px-margin_desktop max-w-container_max_width">
        <!-- Brand Column -->
        <div class="space-y-4">
            <a href="/" class="flex items-center gap-2 mb-6">
                <img src="{{ asset('images/Logo_ASR.png') }}" alt="Logo AantassenaSwimClub" class="object-contain w-12 h-12 rounded-full">
                <span class="font-bold font-headline text-headline-sm text-primary">AantassenaSwimClub</span>
            </a>
            <p class="pr-4 text-on-surface-variant text-body-sm">
                Mencetak perenang tangguh dengan metode aman, menyenangkan, dan profesional sejak 2022. Berbasis standar pelatihan modern.
            </p>
        </div>
        <!-- Quick Links -->
        <div class="space-y-4">
            <h4 class="relative inline-block mb-6 font-semibold font-headline text-headline-sm text-primary">
                Tautan Cepat
                <span class="absolute left-0 w-1/2 h-1 rounded-full -bottom-2 bg-orange"></span>
            </h4>
            <ul class="space-y-3">
                <li><a class="block transition-transform duration-200 cursor-pointer text-on-surface-variant hover:text-primary hover:translate-x-1" href="{{ url('/tentang') }}">Tentang Kami</a></li>
                <li><a class="block transition-transform duration-200 cursor-pointer text-on-surface-variant hover:text-primary hover:translate-x-1" href="{{ url('/program') }}">Program Pelatihan</a></li>
                <li><a class="block transition-transform duration-200 cursor-pointer text-on-surface-variant hover:text-primary hover:translate-x-1" href="{{ url('/kontak') }}">Kontak</a></li>
            </ul>
        </div>
        <!-- Contact Info -->
        <div class="space-y-4">
            <h4 class="relative inline-block mb-6 font-semibold font-headline text-headline-sm text-primary">
                Hubungi Kami
                <span class="absolute left-0 w-1/2 h-1 rounded-full -bottom-2 bg-orange"></span>
            </h4>
            <ul class="space-y-4">
                <li class="flex items-start gap-3 text-on-surface-variant">
                    <span class="mt-1 material-symbols-outlined text-primary shrink-0">location_on</span>
                    <span> {{ \App\Models\User::adminAddress() }}</span>
                </li>
                <li class="flex items-center gap-3 text-on-surface-variant">
                    <span class="material-symbols-outlined text-primary shrink-0">call</span>
                    <a class="transition-colors hover:text-primary" href="{{ \App\Models\User::adminTelLink() }}">{{ \App\Models\User::adminWaDisplay() }} (WA)</a>
                </li>
                <li class="flex items-center gap-3 text-on-surface-variant">
                    <span class="material-symbols-outlined text-primary shrink-0">mail</span>
                    <a class="transition-colors hover:text-primary" href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a>
                </li>
                <li class="flex items-center gap-3 text-on-surface-variant">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="text-primary shrink-0"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <a class="transition-colors hover:text-primary" href="{{ $footerInstagram }}" target="_blank" rel="noopener noreferrer">{{ $footerInstagramHandle }}</a>
                </li>
            </ul>
        </div>
        <!-- Map Placeholder -->
        <div class="space-y-4">
            <h4 class="relative inline-block mb-6 font-semibold font-headline text-headline-sm text-primary">
                Lokasi
                <span class="absolute left-0 w-1/2 h-1 rounded-full -bottom-2 bg-orange"></span>
            </h4>
            <iframe
                src="{{ $footerMapsUrl }}"
                class="w-full h-40 border-0 shadow-sm rounded-xl"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin"
                title="Lokasi AantassenaSwimClub"></iframe>
        </div>
    </div>
    <!-- Copyright -->
    <div class="py-4 mt-8 bg-primary text-on-primary">
        <div class="flex flex-col items-center justify-between gap-2 mx-auto text-center max-w-container_max_width px-margin_mobile md:px-margin_desktop font-body text-label-sm md:flex-row">
            <p>&copy; {{ date('Y') }} AantassenaSwimClub. All rights reserved.</p>
        </div>
    </div>
</footer>
