@if ($slots->isEmpty())
    <p class="font-body-sm text-body-sm text-outline">Belum ada jadwal pertemuan untuk periode ini.</p>
@else
    <div class="grid {{ ($calendar ?? false) ? 'grid-cols-7 gap-1.5' : 'grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2' }}">
        @foreach ($slots as $slot)
            <div class="rounded-lg border p-2 sm:p-3 text-center flex flex-col items-center justify-center gap-0.5 transition-colors {{ $slot['attended'] ? 'bg-[#E8F5E9] border-[#2E7D32]/40' : 'bg-surface-container-low/40 border-dashed border-outline-variant/40' }}">
                @if ($calendar ?? false)
                    <p class="font-headline text-headline-sm {{ $slot['attended'] ? 'text-[#2E7D32]' : 'text-on-surface' }}">{{ $slot['dayNumber'] }}</p>
                @else
                    <p class="font-label-sm text-label-sm text-outline">{{ $slot['label'] }}</p>
                @endif
                <p class="font-label-md text-label-md {{ $slot['attended'] ? 'text-[#2E7D32]' : 'text-outline' }}">{{ $slot['attended'] ? 'Hadir' : 'Belum' }}</p>
                @if (! ($calendar ?? false))
                    <p class="font-body-sm text-body-sm text-outline">{{ $slot['dateLabel'] }}</p>
                @endif
                @if ($slot['attended'])
                    <p class="font-body-sm text-body-sm text-outline truncate max-w-full">{{ $slot['recorder'] ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif
