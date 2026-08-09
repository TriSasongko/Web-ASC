@php
    $linkBase = 'flex items-center gap-3 px-4 py-3 rounded-lg border-l-4 transition-colors group';
    $active = 'border-primary bg-secondary-container/10 text-primary font-bold';
    $inactive = 'border-transparent text-on-surface-variant hover:bg-surface-container-low';

    $items = [
        ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'pelatih.dashboard', 'active' => request()->routeIs('pelatih.dashboard')],
        ['label' => 'Absensi', 'icon' => 'event_available', 'route' => 'pelatih.attendances.index', 'active' => request()->routeIs('pelatih.attendances.*')],
    ];
@endphp

@foreach ($items as $item)
    <a href="{{ route($item['route']) }}"
        class="{{ $linkBase }} {{ $item['active'] ? $active : $inactive }}">
        <span class="material-symbols-outlined {{ $item['active'] ? 'filled' : 'text-outline group-hover:text-primary transition-colors' }}">{{ $item['icon'] }}</span>
        <span class="font-label-md text-label-md flex-1">{{ $item['label'] }}</span>
    </a>
@endforeach

<div x-data="{ open: @json(request()->routeIs('pelatih.developments.*')) }" class="w-full">
    <button @click="open = ! open"
        class="{{ $linkBase }} w-full {{ request()->routeIs('pelatih.developments.*') ? $active : $inactive }}">
        <span class="material-symbols-outlined {{ request()->routeIs('pelatih.developments.*') ? 'filled' : 'text-outline group-hover:text-primary transition-colors' }}">assessment</span>
        <span class="font-label-md text-label-md flex-1 text-left">Perkembangan</span>
        <svg class="h-4 w-4 shrink-0 transition-transform" :class="{ 'rotate-180': open }" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition
        class="mt-1 space-y-1 border-l border-outline-variant/40 pl-3"
        style="display: none;">
        @forelse ($sidebarClasses as $class)
            <a href="{{ route('pelatih.developments.index', $class) }}"
                class="block px-4 py-2 rounded-lg font-label-md text-label-md text-on-surface-variant transition-colors hover:bg-surface-container-low hover:text-primary">
                {{ $class->name }}
            </a>
        @empty
            <p class="px-4 py-2 font-label-sm text-label-sm text-outline">Belum ada kelas.</p>
        @endforelse
    </div>
</div>
