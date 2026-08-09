@php
    $linkBase = 'flex items-center gap-3 px-4 py-3 rounded-lg border-l-4 transition-colors group';
    $active = 'border-primary bg-secondary-container/10 text-primary font-bold';
    $inactive = 'border-transparent text-on-surface-variant hover:bg-surface-container-low';

    $items = [
        ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'orangtua.dashboard', 'active' => request()->routeIs('orangtua.dashboard')],
        ['label' => 'Pendaftaran Anak', 'icon' => 'app_registration', 'route' => 'orangtua.registrations.index', 'active' => request()->routeIs('orangtua.registrations.*')],
        ['label' => 'E-Raport', 'icon' => 'description', 'route' => 'orangtua.eraports.index', 'active' => request()->routeIs('orangtua.eraports.*')],
    ];
@endphp

@foreach ($items as $item)
    <a href="{{ route($item['route']) }}"
        class="{{ $linkBase }} {{ $item['active'] ? $active : $inactive }}">
        <span class="material-symbols-outlined {{ $item['active'] ? 'filled' : 'text-outline group-hover:text-primary transition-colors' }}">{{ $item['icon'] }}</span>
        <span class="font-label-md text-label-md flex-1">{{ $item['label'] }}</span>
    </a>
@endforeach
