@php
    $linkBase = 'flex items-center gap-3 px-4 py-3 rounded-lg border-l-4 transition-colors group';
    $active = 'border-primary bg-secondary-container/10 text-primary font-bold';
    $inactive = 'border-transparent text-on-surface-variant hover:bg-surface-container-low';

    $groups = [
        'UMUM' => [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard')],
        ],
        'KEANGGOTAAN' => [
            ['label' => 'Pendaftaran', 'icon' => 'app_registration', 'route' => 'admin.registrations.index', 'active' => request()->routeIs('admin.registrations.*'), 'badge' => $navPendingRegistrations, 'badgeColor' => 'bg-error text-on-error'],
            ['label' => 'Pelatih', 'icon' => 'sports', 'route' => 'admin.coaches.index', 'active' => request()->routeIs('admin.coaches.*')],
            ['label' => 'Orang Tua', 'icon' => 'family_restroom', 'route' => 'admin.parents.index', 'active' => request()->routeIs('admin.parents.*')],
            ['label' => 'Siswa', 'icon' => 'school', 'route' => 'admin.students.index', 'active' => request()->routeIs('admin.students.*')],
        ],
        'OPERASIONAL LATIHAN' => [
            ['label' => 'Kelas', 'icon' => 'pool', 'route' => 'admin.classes.index', 'active' => request()->routeIs('admin.classes.*') || request()->routeIs('admin.class-students.*'), 'badge' => $navClassesPending, 'badgeColor' => 'bg-error-container text-on-error-container'],
            ['label' => 'Jadwal', 'icon' => 'calendar_month', 'route' => 'admin.schedules.index', 'active' => request()->routeIs('admin.schedules.*')],
            ['label' => 'Absensi', 'icon' => 'event_available', 'route' => 'admin.attendances.index', 'active' => request()->routeIs('admin.attendances.*')],
            ['label' => 'Honor Pelatih', 'icon' => 'payments', 'route' => 'admin.salaries.index', 'active' => request()->routeIs('admin.salaries.*')],
            ['label' => 'Perpanjangan Paket', 'icon' => 'autorenew', 'route' => 'admin.renewals.index', 'active' => request()->routeIs('admin.renewals.*'), 'badge' => $navRenewalsPending, 'badgeColor' => 'bg-error text-on-error'],
        ],
        'EVALUASI & AKADEMIK' => [
            ['label' => 'Perkembangan', 'icon' => 'assessment', 'route' => 'admin.developments.index', 'active' => request()->routeIs('admin.developments.*') || request()->routeIs('admin.classes.developments.*')],
            ['label' => 'Rekomendasi', 'icon' => 'star', 'route' => 'admin.recommendations.index', 'active' => request()->routeIs('admin.recommendations.*')],
            ['label' => 'E-Raport', 'icon' => 'description', 'route' => 'admin.eraports.index', 'active' => request()->routeIs('admin.eraports.*') || request()->routeIs('eraport.*')],
        ],
        'PENGATURAN' => [
            ['label' => 'Landing Page', 'icon' => 'web', 'route' => 'admin.settings.edit', 'active' => request()->routeIs('admin.settings.*')],
        ],
    ];
@endphp

@foreach ($groups as $groupLabel => $items)
    <p class="px-4 pt-5 pb-1 font-label-sm text-label-sm text-outline uppercase tracking-wider text-[11px]">{{ $groupLabel }}</p>
    @foreach ($items as $item)
        <a href="{{ route($item['route']) }}"
            class="{{ $linkBase }} {{ $item['active'] ? $active : $inactive }}">
            <span class="material-symbols-outlined {{ $item['active'] ? 'filled' : 'text-outline group-hover:text-primary transition-colors' }}">{{ $item['icon'] }}</span>
            <span class="font-label-md text-label-md flex-1">{{ $item['label'] }}</span>
            @if (! empty($item['badge']))
                <span class="{{ $item['badgeColor'] }} font-label-sm text-label-sm px-2 py-0.5 rounded-full">{{ $item['badge'] }}</span>
            @endif
        </a>
    @endforeach
@endforeach
