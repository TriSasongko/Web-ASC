<x-sidebar-layout>
    <div class="space-y-6">
        <div>
            <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Jadwal Latihan Anak</h2>
            <p class="font-body-sm text-body-sm text-outline mt-1">Jadwal latihan mingguan anak Anda, dari Senin sampai Minggu.</p>
        </div>

        @forelse ($children as $entry)
            @php
                $child = $entry['child'];
                $schedulesByDay = $entry['schedulesByDay'];
                $totalSessions = $schedulesByDay->flatten()->count();
            @endphp

            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                <div class="px-6 py-5 border-b border-outline-variant/30 bg-surface/50 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-tertiary-fixed text-tertiary flex items-center justify-center font-label-md text-label-md shrink-0">
                        {{ strtoupper(substr($child->full_name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-headline text-headline-sm text-on-surface truncate">{{ $child->full_name }}</h3>
                        <p class="font-body-sm text-body-sm text-outline truncate">
                            {{ $child->classes->pluck('name')->implode(', ') ?: 'Belum ada kelas aktif' }}
                        </p>
                    </div>
                    <span class="font-label-sm text-label-sm text-outline shrink-0">{{ $totalSessions }} sesi</span>
                </div>

                <div class="divide-y divide-outline-variant/30">
                    @foreach ($schedulesByDay as $day => $schedules)
                        @if ($schedules->isEmpty())
                            @continue
                        @endif
                        <div class="px-6 py-2.5 bg-surface/40">
                            <span class="font-label-md text-label-md text-on-surface uppercase tracking-wide">{{ ucfirst($day) }}</span>
                        </div>
                        @foreach ($schedules as $s)
                            <div class="px-6 py-3 flex flex-wrap items-center gap-x-6 gap-y-2 hover:bg-surface-container-low/50 transition-colors">
                                <div class="w-16 shrink-0">
                                    <p class="font-label-md text-label-md text-primary">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}</p>
                                    <p class="font-label-sm text-label-sm text-outline">{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</p>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-label-md text-label-md text-on-surface">{{ $s->schoolClass?->name ?? '-' }}</p>
                                    <p class="font-body-sm text-body-sm text-outline">{{ $s->schoolClass?->program?->name }} · {{ $s->schoolClass?->level_label ?? '-' }} · {{ $s->location ?? '-' }}</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-1.5 shrink-0">
                                    @forelse ($s->coaches as $c)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">{{ $c->name }}</span>
                                    @empty
                                        <span class="font-body-sm text-body-sm text-outline">-</span>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                    @if ($totalSessions === 0)
                        <div class="p-6 text-center">
                            <p class="font-body-sm text-body-sm text-outline">Belum ada jadwal latihan untuk {{ $child->nickname ?: $child->full_name }}.</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-10 text-center">
                <p class="font-body-sm text-body-sm text-outline">Belum ada anak terdaftar.</p>
            </div>
        @endforelse
    </div>
</x-sidebar-layout>
