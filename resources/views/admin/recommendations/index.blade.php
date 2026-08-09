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

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
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
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kelas Saat Ini</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Target</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Dari</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($recommendations as $rec)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $rec->student->full_name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $rec->currentClass->name ?? '-' }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">
                                    {{ $rec->recommendedClass->name ?? 'Level '.($rec->recommended_level ?? '-') }}
                                </td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $rec->from->name }} ({{ $rec->from->isAdmin() ? 'Admin' : 'Pelatih' }})</td>
                                <td class="px-4 py-3">
                                    @if ($rec->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            Menunggu respon orang tua
                                        </span>
                                    @elseif ($rec->status === 'diterima')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                            Disetujui orang tua
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">
                                            <span class="material-symbols-outlined text-[14px]">block</span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-4">
                                        @if ($rec->status === 'diterima')
                                            <a href="{{ route('admin.classes.show', $rec->currentClass ?? $rec->recommendedClass ?? $rec->student->classes()->first()) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                                <span class="material-symbols-outlined text-[16px]">swap_horiz</span>
                                                Pindahkan
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.recommendations.destroy', $rec) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus rekomendasi ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 text-error font-label-md text-label-md hover:underline">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                                Hapus
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
