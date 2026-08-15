@props(['schedulesByDay', 'showClassLink' => true, 'manageable' => false])

@php
    $days = \App\Models\ClassSchedule::DAYS;

    $slotMap = [];
    foreach ($schedulesByDay as $day => $schedules) {
        foreach ($schedules as $s) {
            $start = \Carbon\Carbon::parse($s->start_time)->format('H:i');
            $end = \Carbon\Carbon::parse($s->end_time)->format('H:i');
            $key = $start . '-' . $end;
            $slotMap[$key] = ['key' => $key, 'start' => $start, 'end' => $end];
        }
    }
    usort($slotMap, fn ($a, $b) => strcmp($a['start'], $b['start']));

    $matrix = [];
    foreach ($slotMap as $slot) {
        foreach ($days as $day) {
            $matrix[$slot['key']][$day] = $schedulesByDay[$day]
                ->filter(fn ($s) => \Carbon\Carbon::parse($s->start_time)->format('H:i') === $slot['start']
                    && \Carbon\Carbon::parse($s->end_time)->format('H:i') === $slot['end'])
                ->values();
        }
    }

    $total = $schedulesByDay->flatten()->count();
    $cols = count($days) + 1;
@endphp

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
    {{-- Mobile: daftar jadwal per hari --}}
    <div class="md:hidden divide-y divide-outline-variant/30">
        @if ($total === 0)
            <div class="p-10 text-center font-body-sm text-body-sm text-outline">Belum ada jadwal.</div>
        @else
            @foreach ($schedulesByDay as $day => $schedules)
                @if ($schedules->isNotEmpty())
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-primary text-[18px]">calendar_today</span>
                            <p class="font-label-md text-label-md text-on-surface uppercase tracking-wider">{{ ucfirst($day) }}</p>
                        </div>
                        <div class="space-y-2">
                            @foreach ($schedules as $s)
                                @php
                                    $sStart = \Carbon\Carbon::parse($s->start_time)->format('H:i');
                                    $sEnd = \Carbon\Carbon::parse($s->end_time)->format('H:i');
                                @endphp
                                <div class="rounded-lg border border-outline-variant/30 bg-surface-container-low/50 px-3 py-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            @if ($showClassLink)
                                                <a href="{{ route('admin.classes.show', $s->schoolClass) }}" class="font-label-md text-label-md text-on-surface hover:text-primary block leading-tight truncate">{{ $s->schoolClass?->name ?? '-' }}</a>
                                            @else
                                                <span class="font-label-md text-label-md text-on-surface block leading-tight truncate">{{ $s->schoolClass?->name ?? '-' }}</span>
                                            @endif
                                            <p class="text-[11px] leading-tight text-outline mt-0.5">{{ $s->schoolClass?->level_label ?? '-' }}</p>
                                        </div>
                                        <span class="shrink-0 inline-flex items-center gap-1 font-label-sm text-label-sm text-on-surface-variant whitespace-nowrap">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            {{ $sStart }}–{{ $sEnd }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] leading-tight text-on-surface-variant mt-1">
                                        @if ($s->students->isEmpty())
                                            <span class="text-outline">Belum ada siswa</span>
                                        @else
                                            {{ $s->students->count() }} siswa
                                        @endif
                                    </p>
                                    @if ($manageable)
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = true" type="button"
                                                    class="inline-flex items-center justify-center gap-0.5 bg-surface-container text-on-surface-variant px-2 py-1 rounded font-label-sm text-label-sm hover:bg-surface-container-high transition-all active:scale-95">
                                                    <span class="material-symbols-outlined text-[14px]">tune</span>
                                                    Atur
                                                </button>
                                                @include('admin.schedules._assign_modal', ['s' => $s, 'coaches' => $coaches])
                                            </div>
                                            <form action="{{ route('admin.schedules.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center gap-0.5 text-error font-label-sm text-label-sm px-2 py-1 rounded border border-error/30 hover:bg-error/10 transition-all">
                                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    {{-- Desktop: matriks jadwal --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full table-fixed text-left min-w-[880px]">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="sticky left-0 z-10 bg-surface-container-low px-3 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider w-24 text-center">Jam</th>
                    @foreach ($days as $day)
                        <th class="px-3 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-center">{{ ucfirst($day) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                @if ($total === 0)
                    <tr>
                        <td colspan="{{ $cols }}" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada jadwal.</td>
                    </tr>
                @else
                    @foreach ($slotMap as $slot)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="sticky left-0 z-10 bg-surface-container-lowest px-3 py-2 text-[11px] text-on-surface whitespace-nowrap align-middle text-center font-semibold w-24">
                                <span class="flex items-center justify-center">{{ $slot['start'] }} – {{ $slot['end'] }}</span>
                            </td>
                            @foreach ($days as $day)
                                @php $cell = $matrix[$slot['key']][$day]; @endphp
                                <td class="px-2.5 py-2 {{ $cell->isEmpty() ? 'align-middle' : 'align-top' }}">
                                    @forelse ($cell as $s)
                                        <div class="mb-1 last:mb-0 rounded-md border border-outline-variant/30 bg-surface-container-low/50 px-2 py-1.5">
                                            @if ($showClassLink)
                                                <a href="{{ route('admin.classes.show', $s->schoolClass) }}" class="font-label-sm text-label-sm text-on-surface hover:text-primary block leading-tight">{{ $s->schoolClass?->name ?? '-' }}</a>
                                            @else
                                                <span class="font-label-sm text-label-sm text-on-surface block leading-tight">{{ $s->schoolClass?->name ?? '-' }}</span>
                                            @endif
                                            <p class="text-[11px] leading-tight text-outline mt-0.5">{{ $s->schoolClass?->level_label ?? '-' }}</p>
                                            <p class="text-[11px] leading-tight text-on-surface-variant mt-0.5">
                                                @if ($s->students->isEmpty())
                                                    <span class="text-outline">Belum ada siswa</span>
                                                @else
                                                    {{ $s->students->count() }} siswa
                                                @endif
                                            </p>
                                            @if ($manageable)
                                                <div class="mt-1 flex flex-wrap items-center gap-1">
                                                    <div x-data="{ open: false }" class="relative">
                                                        <button @click="open = true" type="button"
                                                            class="inline-flex items-center justify-center gap-0.5 bg-surface-container text-on-surface-variant px-1.5 py-0.5 rounded font-label-sm text-label-sm hover:bg-surface-container-high transition-all active:scale-95">
                                                            <span class="material-symbols-outlined text-[12px]">tune</span>
                                                            Atur
                                                        </button>
                                                        @include('admin.schedules._assign_modal', ['s' => $s, 'coaches' => $coaches])
                                                    </div>
                                                    <form action="{{ route('admin.schedules.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center justify-center gap-0.5 text-error font-label-sm text-label-sm px-1.5 py-0.5 rounded border border-error/30 hover:bg-error/10 transition-all">
                                                            <span class="material-symbols-outlined text-[12px]">delete</span>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="flex items-center justify-center text-[11px] text-outline">-</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
