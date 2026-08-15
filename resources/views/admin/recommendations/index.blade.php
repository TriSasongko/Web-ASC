<x-sidebar-layout>
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

            {{-- Mobile: kartu rekomendasi --}}
            <div class="md:hidden divide-y divide-outline-variant/30">
                @forelse ($recommendations as $rec)
                    @php
                        $targetLabel = $rec->recommendedClass?->name
                            ?? (\App\Models\SchoolClass::levelOptions()[$rec->recommended_level] ?? 'Level '.($rec->recommended_level ?? '-'));
                        $targetLevel = $rec->recommendedClass?->level
                            ?? $rec->recommended_level;
                        $currentLevel = $rec->currentClass?->level;
                    @endphp
                    <div class="p-4 hover:bg-surface-container-low/50 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-label-md text-label-md text-on-surface truncate">{{ $rec->student?->full_name }}</p>
                                <p class="font-body-sm text-body-sm text-outline truncate mt-0.5">{{ $rec->currentClass?->name ?? 'Tanpa kelas aktif' }}</p>
                            </div>
                            @if ($rec->status === 'pending')
                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    Menunggu
                                </span>
                            @elseif ($rec->status === 'menunggu_ortu')
                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E3F2FD] text-[#1565C0]">
                                    <span class="material-symbols-outlined text-[14px]">forum</span>
                                    Menunggu ortu
                                </span>
                            @elseif ($rec->status === 'diterima')
                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    Disetujui
                                </span>
                            @else
                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">
                                    <span class="material-symbols-outlined text-[14px]">block</span>
                                    Ditolak
                                </span>
                            @endif
                        </div>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $currentLevel ? $currentLevel.' → ' : '' }}</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container/60 text-on-primary">{{ $targetLabel }}</span>
                            @if ($targetLevel !== null && $targetLevel !== $currentLevel)
                                <span class="font-body-sm text-body-sm text-outline">(Level {{ $targetLevel }})</span>
                            @endif
                        </div>
                        <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 font-body-sm text-body-sm text-on-surface">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px] text-outline">person</span>
                                {{ $rec->from?->name }}
                            </span>
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">
                                {{ $rec->from?->isAdmin() ? 'Admin' : 'Pelatih' }}
                            </span>
                        </div>
                        @if ($rec->note)
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">{{ $rec->note }}</p>
                        @endif
                        <div class="mt-3">
                            @include('admin.recommendations._actions', ['rec' => $rec])
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center font-body-sm text-body-sm text-outline">Belum ada rekomendasi.</div>
                @endforelse
            </div>

            {{-- Desktop: tabel rekomendasi --}}
            <div class="hidden md:block overflow-x-auto">
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
                                    <div>
                                        <p class="font-label-md text-label-md text-on-surface">{{ $rec->student?->full_name }}</p>
                                        <p class="font-body-sm text-body-sm text-outline">{{ $rec->currentClass?->name ?? 'Tanpa kelas aktif' }}</p>
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
                                    @include('admin.recommendations._actions', ['rec' => $rec])
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
