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
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-6">{{ $settings['galeri_heading'] }}</h1>
                    <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">
                        {{ $settings['galeri_subtitle'] }}
                    </p>
                </header>

                <!-- Gallery Filter -->
                <div class="flex flex-wrap justify-center gap-3 mb-12">
                    <button @click="filter = 'Semua'"
                        class="px-6 py-1.5 rounded-full font-body text-label-md transition-colors"
                        x-bind:class="filter === 'Semua' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'">
                        Semua
                    </button>
                    @foreach ($gallery->pluck('category')->unique()->filter() as $category)
                        <button @click="filter = '{{ $category }}'"
                            class="px-6 py-1.5 rounded-full font-body text-label-md transition-colors"
                            x-bind:class="filter === '{{ $category }}' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>

                <!-- Masonry Gallery -->
                <div class="masonry-grid">
                    @forelse ($gallery as $image)
                        <div class="masonry-item pool-shadow bg-surface rounded-xl overflow-hidden group cursor-pointer border border-surface-variant transition-transform hover:-translate-y-1 duration-300" x-show="filter === 'Semua' || filter === '{{ $image->category }}'">
                            <div class="relative overflow-hidden {{ $image->aspectClass() }}">
                                <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     alt="{{ $image->title }}"
                                     src="{{ $image->image_url }}">
                                @if ($image->aspect === 'video')
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-colors">
                                        <span class="material-symbols-outlined text-white text-[48px] drop-shadow-md">play_circle</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 bg-surface">
                                <span class="inline-block px-3 py-0.5 bg-tertiary-fixed text-primary font-body text-label-sm rounded-full mb-3">{{ $image->category }}</span>
                                <h3 class="font-headline text-headline-sm text-on-surface mb-2">{{ $image->title }}</h3>
                                <p class="font-body text-body-md text-on-surface-variant text-sm">{{ $image->description }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-on-surface-variant">Belum ada foto galeri.</p>
                    @endforelse
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
