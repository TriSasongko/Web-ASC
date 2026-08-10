<x-sidebar-layout>
    @php
        $initials = fn ($name) => collect(explode(' ', (string) $name))
            ->filter()
            ->map(fn ($word) => mb_substr($word, 0, 1))
            ->take(2)
            ->join('');
    @endphp
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Rekomendasi Naik Kelas</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Daftar rekomendasi kenaikan kelas dan status persetujuannya.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-2 bg-[#FFEBEE] text-[#C62828] border border-[#C62828]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">north_east</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Data Rekomendasi</h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Perpindahan</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Diajukan oleh</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Catatan</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($recommendations as $rec)
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
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-primary-container text-on-primary font-label-md text-label-md shrink-0">
                                            {{ $initials($rec->student?->full_name) }}
                                        </span>
                                        <div>
                                            <p class="font-label-md text-label-md text-on-surface">{{ $rec->student?->full_name }}</p>
                                            <p class="font-body-sm text-body-sm text-outline">{{ $rec->currentClass?->name ?? 'Tanpa kelas aktif' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $currentLevel ? $currentLevel.' → ' : '' }}</span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container/60 text-on-primary">{{ $targetLabel }}</span>
                                        @if ($targetLevel !== null && $targetLevel !== $currentLevel)
                                            <span class="font-body-sm text-body-sm text-outline">(Level {{ $targetLevel }})</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-body-sm text-body-sm text-on-surface">{{ $rec->from?->name }}</span>
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">
                                            {{ $rec->from?->isAdmin() ? 'Admin' : 'Pelatih' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($rec->note)
                                        <p class="font-body-sm text-body-sm text-on-surface-variant max-w-[220px] truncate" title="{{ $rec->note }}">{{ $rec->note }}</p>
                                    @else
                                        <span class="font-body-sm text-body-sm text-outline">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($rec->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            Menunggu
                                        </span>
                                    @elseif ($rec->status === 'menunggu_ortu')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E3F2FD] text-[#1565C0]">
                                            <span class="material-symbols-outlined text-[14px]">forum</span>
                                            Menunggu konfirmasi ortu
                                        </span>
                                    @elseif ($rec->status === 'diterima')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">
                                            <span class="material-symbols-outlined text-[14px]">block</span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-1.5 whitespace-nowrap">
                                        @if ($rec->currentClass)
                                            <a href="{{ route('admin.classes.developments.history', [$rec->currentClass, $rec->student]) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg font-label-sm text-label-sm text-primary hover:bg-primary-container/40 transition-all" title="Lihat perkembangan">
                                                <span class="material-symbols-outlined text-[16px]">assessment</span>
                                                Perkembangan
                                            </a>
                                        @else
                                            <a href="{{ route('admin.students.show', $rec->student) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg font-label-sm text-label-sm text-primary hover:bg-primary-container/40 transition-all" title="Lihat detail siswa">
                                                <span class="material-symbols-outlined text-[16px]">person</span>
                                                Detail
                                            </a>
                                        @endif

                                        @if ($rec->status === 'pending')
                                            <form action="{{ route('admin.recommendations.approve', $rec) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Setujui rekomendasi? Siswa dipindahkan setelah orang tua mengonfirmasi.')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32] hover:opacity-90 transition-all active:scale-95">
                                                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.recommendations.reject', $rec) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Tolak rekomendasi ini?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg font-label-sm text-label-sm bg-error-container text-on-error-container hover:opacity-90 transition-all active:scale-95">
                                                    <span class="material-symbols-outlined text-[16px]">block</span>
                                                    Tolak
                                                </button>
                                            </form>
                                        @elseif ($rec->status === 'menunggu_ortu')
                                            @if ($waUrl)
                                                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg font-label-sm text-label-sm bg-[#25D366] text-white hover:opacity-90 transition-all active:scale-95" title="Konfirmasi ke orang tua via WhatsApp">
                                                    <span class="material-symbols-outlined text-[16px]">chat</span>
                                                    Konfirmasi WA
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.recommendations.confirm', $rec) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Orang tua sudah konfirmasi? Siswa akan dipindahkan ke kelas target.')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32] hover:opacity-90 transition-all active:scale-95">
                                                    <span class="material-symbols-outlined text-[16px]">verified</span>
                                                    Selesaikan
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.recommendations.reject', $rec) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Tandai bahwa orang tua menolak? Siswa tetap di kelas sekarang.')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg font-label-sm text-label-sm bg-error-container text-on-error-container hover:opacity-90 transition-all active:scale-95">
                                                    <span class="material-symbols-outlined text-[16px]">block</span>
                                                    Ortu menolak
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.recommendations.destroy', $rec) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus rekomendasi ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-error hover:bg-error-container/50 transition-all" title="Hapus">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center font-body-sm text-body-sm text-outline">Belum ada rekomendasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30">{{ $recommendations->links() }}</div>
        </div>
    </div>
</x-sidebar-layout>
