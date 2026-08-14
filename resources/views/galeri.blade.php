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

        @php
            $galleryItems = $gallery->map(fn ($g) => [
                'url' => $g->image_url,
                'title' => $g->title,
                'description' => $g->description,
                'category' => $g->category,
                'video' => $g->aspect === 'video',
            ])->values();
        @endphp
        <script>
            window.__galleryItems = @json($galleryItems);
        </script>

        <!-- Main Content -->
        <main class="flex-grow pb-16 md:pb-24"
              x-data="{
                  filter: 'Semua',
                  current: 0,
                  openLightbox: false,
                  images: window.__galleryItems,
                  page: 1,
                  perPage: window.innerWidth < 768 ? 6 : 12,
                  open(i) {
                      this.current = i;
                      this.openLightbox = true;
                      document.body.style.overflow = 'hidden';
                  },
                  close() {
                      this.openLightbox = false;
                      document.body.style.overflow = '';
                  },
                  prev() {
                      this.current = (this.current - 1 + this.images.length) % this.images.length;
                  },
                  next() {
                      this.current = (this.current + 1) % this.images.length;
                  },
                  filteredIndices() {
                      const list = [];
                      this.images.forEach((img, i) => {
                          if (this.filter === 'Semua' || img.category === this.filter) list.push(i);
                      });
                      return list;
                  },
                  totalPages() {
                      return Math.max(1, Math.ceil(this.filteredIndices().length / this.perPage));
                  },
                  isShown(i) {
                      const list = this.filteredIndices();
                      const pos = list.indexOf(i);
                      return pos >= (this.page - 1) * this.perPage && pos < this.page * this.perPage;
                  },
                  clampPage() {
                      if (this.page > this.totalPages()) this.page = this.totalPages();
                      if (this.page < 1) this.page = 1;
                  },
                  prevPage() {
                      if (this.page > 1) this.page--;
                  },
                  nextPage() {
                      if (this.page < this.totalPages()) this.page++;
                  },
                  setPerPage() {
                      const p = window.innerWidth < 768 ? 6 : 12;
                      if (p !== this.perPage) {
                          this.perPage = p;
                          this.clampPage();
                      }
                  },
                  init() {
                      this.clampPage();
                      window.addEventListener('resize', () => this.setPerPage());
                  }
              }"
              @keydown.escape.window="close()"
              @keydown.arrow-left.window="openLightbox && prev()"
              @keydown.arrow-right.window="openLightbox && next()">
            <div class="max-w-container_max_width mx-auto px-margin_mobile md:px-margin_desktop">
                <!-- Hero -->
                <section class="relative text-center pt-8 md:pt-12 pb-10 md:pb-14 overflow-hidden">
                    <div class="absolute inset-0 -z-10 pointer-events-none">
                        <div class="absolute -top-20 -left-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
                        <div class="absolute top-6 -right-24 w-80 h-80 bg-orange/10 rounded-full blur-3xl"></div>
                    </div>
                    <span class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-1.5 rounded-full font-body text-label-md font-semibold mb-5">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                        Momen &amp; Kegiatan
                    </span>
                    <h1 class="font-headline text-headline-lg-mobile md:text-headline-xl text-primary mb-4">{{ $settings['galeri_heading'] }}</h1>
                    <p class="font-body text-body-lg text-on-surface-variant max-w-2xl mx-auto">
                        {{ $settings['galeri_subtitle'] }}
                    </p>
                </section>

                <!-- Gallery Filter -->
                <div class="flex flex-wrap justify-center gap-3 mb-8 md:mb-10">
                    <button @click="filter = 'Semua'; page = 1"
                        class="px-6 py-1.5 rounded-full font-body text-label-md transition-colors"
                        x-bind:class="filter === 'Semua' ? 'bg-primary text-on-primary shadow-md shadow-primary/20' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'">
                        Semua
                    </button>
                    @foreach ($gallery->pluck('category')->unique()->filter() as $category)
                        <button @click="filter = '{{ $category }}'; page = 1"
                            class="px-6 py-1.5 rounded-full font-body text-label-md transition-colors"
                            x-bind:class="filter === '{{ $category }}' ? 'bg-primary text-on-primary shadow-md shadow-primary/20' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest'">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>

                <!-- Mosaic Gallery -->
                <div class="grid grid-cols-2 md:grid-cols-4 auto-rows-[110px] sm:auto-rows-[140px] md:auto-rows-[180px] gap-3 md:gap-4">
                    @forelse ($gallery as $index => $image)
                        <button type="button"
                            class="relative overflow-hidden rounded-2xl group text-left bg-surface-container-high {{ $index % 5 === 0 ? 'md:col-span-2 md:row-span-2' : '' }}"
                            x-show="isShown({{ $index }})"
                            @click="open({{ $index }})">
                            <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 alt="{{ $image->title }}"
                                 loading="lazy"
                                 src="{{ $image->image_url }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                            @if ($image->aspect === 'video')
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-[40px] drop-shadow-lg group-hover:scale-110 transition-transform">play_circle</span>
                                </div>
                            @endif
                            <div class="absolute inset-x-0 bottom-0 p-3 md:p-4">
                                <span class="inline-block px-2 py-0.5 bg-orange text-white text-[10px] font-bold rounded-full mb-1.5">{{ $image->category }}</span>
                                <h3 class="text-white font-headline text-label-md md:text-headline-sm font-bold leading-snug line-clamp-2">{{ $image->title }}</h3>
                            </div>
                        </button>
                    @empty
                        <p class="col-span-full text-center text-on-surface-variant">Belum ada foto galeri.</p>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div x-show="totalPages() > 1"
                     class="flex items-center justify-center gap-3 md:gap-4 mt-8 md:mt-10">
                    <button type="button" @click="prevPage()" :disabled="page === 1"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant font-body text-label-md transition-colors hover:bg-surface-container-highest disabled:opacity-40 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        Sebelumnya
                    </button>
                    <span class="font-body text-label-md text-on-surface-variant px-2" x-text="'Halaman ' + page + ' dari ' + totalPages()"></span>
                    <button type="button" @click="nextPage()" :disabled="page === totalPages()"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant font-body text-label-md transition-colors hover:bg-surface-container-highest disabled:opacity-40 disabled:cursor-not-allowed">
                        Selanjutnya
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>

            <!-- Lightbox -->
            <div x-show="openLightbox"
                 x-cloak
                 x-transition.opacity.duration.200ms
                 class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center p-4 md:p-10"
                 @click.self="close()">
                <button type="button" @click="close()" aria-label="Tutup"
                    class="absolute top-4 right-4 z-10 w-11 h-11 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
                <button type="button" @click.prevent="prev()" aria-label="Sebelumnya"
                    class="absolute left-2 md:left-6 top-1/2 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button type="button" @click.prevent="next()" aria-label="Berikutnya"
                    class="absolute right-2 md:right-6 top-1/2 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>

                <figure class="max-w-5xl w-full">
                    <div class="relative">
                        <img x-bind:src="images[current].url"
                             x-bind:alt="images[current].title"
                             class="w-full max-h-[70vh] object-contain rounded-xl shadow-2xl">
                        <template x-if="images[current].video">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-[72px] drop-shadow-xl">play_circle</span>
                            </div>
                        </template>
                    </div>
                    <figcaption class="text-center mt-5 text-white">
                        <span class="inline-block px-3 py-0.5 bg-orange text-white text-[10px] font-bold rounded-full mb-2" x-text="images[current].category"></span>
                        <h3 class="font-headline text-headline-md md:text-headline-lg font-bold" x-text="images[current].title"></h3>
                        <p class="text-white/70 mt-1.5 max-w-2xl mx-auto" x-text="images[current].description"></p>
                        <p class="text-white/40 text-label-sm mt-4" x-text="(current + 1) + ' / ' + images.length"></p>
                    </figcaption>
                </figure>
            </div>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </body>
</html>
