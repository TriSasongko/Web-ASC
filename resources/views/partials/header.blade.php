<!-- Navbar -->
<nav class="fixed top-0 w-full z-50 bg-surface/90 backdrop-blur-md border-b border-outline-variant/30 shadow-[0_4px_20px_rgba(0,71,169,0.08)]"
     x-data="{ mobileMenuOpen: false }">
    @php
        $navItems = [
            ['label' => 'Home', 'href' => url('/'), 'active' => request()->is('/')],
            ['label' => 'Tentang', 'href' => url('/tentang'), 'active' => request()->is('tentang')],
            ['label' => 'Program', 'href' => url('/program'), 'active' => request()->is('program')],
            ['label' => 'Galeri', 'href' => url('/galeri'), 'active' => request()->is('galeri')],
            ['label' => 'FAQ', 'href' => url('/faq'), 'active' => request()->is('faq')],
            ['label' => 'Kontak', 'href' => url('/kontak'), 'active' => request()->is('kontak')],
        ];
    @endphp
    <div class="flex items-center justify-between py-4 mx-auto max-w-container_max_width px-margin_mobile md:px-margin_desktop">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 min-w-0">
            <img src="{{ asset('images/Logo_ASR.png') }}" alt="Logo AantassenaSwimClub" class="w-10 h-10 shrink-0 object-contain">
                <span class="font-bold font-headline text-headline-sm md:text-headline-md text-primary truncate">AantassenaSwimClub</span>
        </a>

        <!-- Desktop Links -->
        <div class="items-center hidden gap-6 md:flex font-body text-label-md">
            @foreach ($navItems as $item)
                <a class="{{ $item['active'] ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
            @endforeach
        </div>

        <!-- Actions -->
        <div class="items-center hidden gap-4 md:flex">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="inline-flex items-center gap-2 bg-primary-container text-on-primary px-5 py-2.5 rounded-lg font-body text-label-md hover:opacity-90 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-5 py-2 transition-colors border-2 rounded-lg font-body text-label-md text-primary border-primary hover:bg-primary-container hover:text-on-primary">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="px-6 py-2 text-white transition-colors rounded-lg shadow-sm font-body text-label-md bg-orange hover:bg-orange-light">
                    Daftar
                </a>
            @endauth
        </div>

        <!-- Mobile Menu Toggle -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 md:hidden text-primary" aria-label="Menu">
            <span class="text-2xl material-symbols-outlined" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div class="absolute left-0 w-full border-b shadow-lg md:hidden bg-surface top-full border-outline-variant/30"
         x-cloak x-show="mobileMenuOpen" x-transition>
        <div class="flex flex-col py-4 space-y-4 px-margin_mobile">
            @foreach ($navItems as $item)
                <a class="{{ $item['active'] ? 'text-primary font-semibold border-l-4 border-primary pl-2' : 'text-on-surface-variant hover:text-primary pl-2' }}" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
            @endforeach
            <hr class="border-outline-variant/30">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="py-2 text-center rounded-lg font-body text-label-md bg-primary-container text-on-primary">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="py-2 text-center border-2 rounded-lg font-body text-label-md text-primary border-primary">Login</a>
                <a href="{{ route('register') }}" class="py-2 text-center text-white rounded-lg font-body text-label-md bg-orange">Daftar Sekarang</a>
            @endauth
        </div>
    </div>
</nav>
