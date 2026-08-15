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

<div class="flex items-center justify-end gap-1 flex-wrap">
    @if ($rec->currentClass)
        <a href="{{ route('admin.classes.developments.history', [$rec->currentClass, $rec->student]) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-outline-variant/40 text-on-surface-variant hover:bg-surface-container transition-all" title="Lihat perkembangan">
            <span class="material-symbols-outlined text-[18px]">assessment</span>
        </a>
    @else
        <a href="{{ route('admin.students.show', $rec->student) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-outline-variant/40 text-on-surface-variant hover:bg-surface-container transition-all" title="Lihat detail siswa">
            <span class="material-symbols-outlined text-[18px]">person</span>
        </a>
    @endif

    @if ($rec->status === 'pending')
        <form action="{{ route('admin.recommendations.approve', $rec) }}" method="POST"
              onsubmit="return confirmRecommendationApprove(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}')">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-container text-on-primary hover:opacity-90 transition-all" title="Setujui">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
            </button>
        </form>
        <form action="{{ route('admin.recommendations.reject', $rec) }}" method="POST"
              onsubmit="return confirmRecommendationReject(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}')">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-error-container text-on-error-container hover:opacity-90 transition-all" title="Tolak">
                <span class="material-symbols-outlined text-[18px]">block</span>
            </button>
        </form>
    @elseif ($rec->status === 'menunggu_ortu')
        @if ($waUrl)
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#E8F5E9] text-[#2E7D32] hover:opacity-90 transition-all" title="Konfirmasi ke orang tua via WhatsApp">
                <span class="material-symbols-outlined text-[18px]">chat</span>
            </a>
        @endif
        <form action="{{ route('admin.recommendations.confirm', $rec) }}" method="POST"
              onsubmit="return confirmRecommendationConfirm(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}', '{{ $targetLabel }}')">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-container text-on-primary hover:opacity-90 transition-all" title="Selesaikan">
                <span class="material-symbols-outlined text-[18px]">verified</span>
            </button>
        </form>
        <form action="{{ route('admin.recommendations.reject', $rec) }}" method="POST"
              onsubmit="return confirmRecommendationRejectByParent(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}')">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-error-container text-on-error-container hover:opacity-90 transition-all" title="Ortu menolak">
                <span class="material-symbols-outlined text-[18px]">block</span>
            </button>
        </form>
    @endif

    <form action="{{ route('admin.recommendations.destroy', $rec) }}" method="POST"
          onsubmit="return confirmRecommendationDelete(event, this, '{{ $rec->student?->full_name ?? 'Siswa' }}')">
        @csrf @method('DELETE')
        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-error border border-error/30 hover:bg-error-container/50 transition-all" title="Hapus">
            <span class="material-symbols-outlined text-[18px]">delete</span>
        </button>
    </form>
</div>
