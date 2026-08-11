@php
    $bySession = $records->keyBy('session_number');
@endphp

@if ($total)
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2">
        @for ($i = 1; $i <= $total; $i++)
            @php
                $rec = $bySession->get($i);
            @endphp
            <div class="rounded-lg border p-3 text-center flex flex-col items-center justify-center gap-0.5 transition-colors {{ $rec ? 'bg-[#E8F5E9] border-[#2E7D32]/40' : 'bg-surface-container-low/40 border-dashed border-outline-variant/40' }}">
                <p class="font-label-sm text-label-sm text-outline">Sesi {{ $i }}</p>
                <p class="font-label-md text-label-md {{ $rec ? 'text-[#2E7D32]' : 'text-outline' }}">{{ $rec ? 'Hadir' : 'Belum' }}</p>
                @if ($rec)
                    <p class="font-body-sm text-body-sm text-outline">{{ $rec->attendance_date->format('d/m/Y') }}</p>
                    <p class="font-body-sm text-body-sm text-outline truncate max-w-full">{{ $rec->recorder?->name ?? '-' }}</p>
                @endif
            </div>
        @endfor
    </div>
@else
    <p class="font-body-sm text-body-sm text-outline">Belum ada catatan absensi untuk periode ini.</p>
@endif
