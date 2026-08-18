<x-sidebar-layout>
    <div class="space-y-6">
        <div>
            <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Pengaturan Landing Page</h2>
            <p class="font-body-sm text-body-sm text-outline mt-1">Kelola konten halaman depan website (hero, tentang, program, galeri, jadwal, dan kontak).</p>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-center gap-2 bg-error-container text-on-error-container border border-error/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">error</span>
                Periksa kembali isian form: {{ $errors->first() }}
            </div>
        @endif

        {{-- Tab Navigasi --}}
        <div class="flex gap-1 overflow-x-auto bg-surface-container-low rounded-xl p-1.5">
            @php
                $tabs = [
                    'hero' => ['label' => 'Hero', 'icon' => 'image'],
                    'tentang' => ['label' => 'Tentang & Coach', 'icon' => 'groups'],
                    'program' => ['label' => 'Program', 'icon' => 'pool'],
                    'galeri' => ['label' => 'Galeri', 'icon' => 'photo_library'],
                    'jadwal' => ['label' => 'Jadwal', 'icon' => 'calendar_month'],
                    'kontak' => ['label' => 'Kontak', 'icon' => 'contact_phone'],
                    'syarat' => ['label' => 'Syarat & Ketentuan', 'icon' => 'gavel'],
                ];
            @endphp
            @foreach ($tabs as $key => $item)
                <a href="{{ route('admin.settings.edit', ['tab' => $key]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg font-label-md text-label-md whitespace-nowrap transition-colors {{ $tab === $key ? 'bg-surface-container-lowest text-primary shadow-sm font-bold' : 'text-on-surface-variant hover:bg-surface-container-lowest/50' }}">
                    <span class="material-symbols-outlined text-[18px] {{ $tab === $key ? 'filled' : '' }}">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        @switch($tab)
            @case('tentang')
                @include('admin.settings.tabs.tentang')
                @break
            @case('program')
                @include('admin.settings.tabs.program')
                @break
            @case('galeri')
                @include('admin.settings.tabs.galeri')
                @break
            @case('jadwal')
                @include('admin.settings.tabs.jadwal')
                @break
            @case('kontak')
                @include('admin.settings.tabs.kontak')
                @break
            @case('syarat')
                @include('admin.settings.tabs.syarat')
                @break
            @default
                @include('admin.settings.tabs.hero')
        @endswitch
    </div>
</x-sidebar-layout>
