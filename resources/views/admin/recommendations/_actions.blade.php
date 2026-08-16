@php
    $targetLabel = $rec->recommendedClass?->name
        ?? (\App\Models\SchoolClass::levelOptions()[$rec->recommended_level] ?? 'Level '.($rec->recommended_level ?? '-'));
    $targetLevel = $rec->recommendedClass?->level
        ?? $rec->recommended_level;
    $currentLevel = $rec->currentClass?->level;
    $parentPhone = $rec->student?->parent?->phone;
    $targetProgram = $rec->recommendedClass?->program;
    $waMessage = rawurlencode(
        'Halo, kami dari ASC Academy ingin mengonfirmasi kenaikan level anak Anda '
        .($rec->student?->full_name ?? 'Ananda')
        .' dari '
        .(($rec->currentClass?->name ?? 'Kelas') . ($currentLevel ? ' (Level '.$currentLevel.')' : ''))
        .' ke '
        .$targetLabel.($targetLevel ? ' (Level '.$targetLevel.')' : '')
        .'. Karena naik level berarti berpindah ke program '
        .($targetProgram?->name ?? 'Kompetitif')
        .($targetProgram
            ? ' dengan biaya '.($targetProgram->billing_type === 'per_bulan' ? 'bulanan' : 'per paket').' Rp '.number_format((float) $targetProgram->price, 0, ',', '.')
            : '')
        .', mohon konfirmasi persetujuan Anda. Terima kasih.'
    );
    $waUrl = $parentPhone
        ? 'https://wa.me/'.preg_replace('/\D/', '', $parentPhone).'?text='.$waMessage
        : null;
@endphp

<div class="flex justify-end" x-data="{ open: false, pos: { top: 0, right: 0 } }" @click.outside="open = false">
    <button type="button" x-ref="trigger"
        @click="open = ! open; if (open) { const r = $refs.trigger.getBoundingClientRect(); pos = { top: r.bottom + 6, right: window.innerWidth - r.right }; }"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-outline-variant/40 text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-all font-label-sm text-label-sm"
        title="Aksi" aria-haspopup="true" :aria-expanded="open.toString()">
        <span class="material-symbols-outlined text-[16px]">more_vert</span>
        Aksi
        <span class="material-symbols-outlined text-[16px] transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div x-show="open" x-cloak x-transition
        @keydown.escape.window="open = false" @click="open = false"
        :style="`position: fixed; top: ${pos.top}px; right: ${pos.right}px; z-index: 90;`"
        class="w-56 py-1.5 bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-lg">
        @if ($rec->currentClass)
            <a href="{{ route('admin.classes.developments.history', [$rec->currentClass, $rec->student]) }}"
                class="flex items-center gap-2.5 px-3.5 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined text-[18px] text-outline">assessment</span>
                Lihat Perkembangan
            </a>
        @else
            <a href="{{ route('admin.students.show', $rec->student) }}"
                class="flex items-center gap-2.5 px-3.5 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined text-[18px] text-outline">person</span>
                Lihat Detail Siswa
            </a>
        @endif

        @if ($rec->status === 'pending')
            <div class="mx-3.5 my-1 border-t border-outline-variant/30"></div>
            <form action="{{ route('admin.recommendations.approve', $rec) }}" method="POST"
                  onsubmit="return confirmRecommendationApprove(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}')">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-primary">check_circle</span>
                    Setujui
                </button>
            </form>
            <form action="{{ route('admin.recommendations.reject', $rec) }}" method="POST"
                  onsubmit="return confirmRecommendationReject(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}')">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-error">block</span>
                    Tolak
                </button>
            </form>
        @elseif ($rec->status === 'menunggu_ortu')
            <div class="mx-3.5 my-1 border-t border-outline-variant/30"></div>
            @if ($waUrl)
                <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                    class="flex items-center gap-2.5 px-3.5 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-[#2E7D32]">chat</span>
                    Konfirmasi ke WhatsApp
                </a>
            @endif
            <form action="{{ route('admin.recommendations.confirm', $rec) }}" method="POST"
                  onsubmit="return confirmRecommendationConfirm(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}', '{{ $targetLabel }}')">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-primary">verified</span>
                    Selesaikan
                </button>
            </form>
            <form action="{{ route('admin.recommendations.reject', $rec) }}" method="POST"
                  onsubmit="return confirmRecommendationRejectByParent(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}')">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-error">block</span>
                    Ortu Menolak
                </button>
            </form>
        @endif

        <div class="mx-3.5 my-1 border-t border-outline-variant/30"></div>
        <form action="{{ route('admin.recommendations.destroy', $rec) }}" method="POST"
              onsubmit="return confirmRecommendationDelete(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}')">
            @csrf @method('DELETE')
            <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 font-body-sm text-body-sm text-error hover:bg-surface-container-low transition-colors text-left">
                <span class="material-symbols-outlined text-[18px]">delete</span>
                Hapus
            </button>
        </form>
    </div>
</div>
