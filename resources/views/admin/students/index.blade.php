<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Data Siswa</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola dan pantau data siswa beserta paketnya.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <form method="GET" class="flex items-center gap-2">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama siswa..." class="w-full sm:w-64 pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant/50 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary font-body-sm text-body-sm transition-all">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2 rounded-full font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Orang Tua</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">No. HP</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Paket</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($students as $student)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $student->full_name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $student->parent->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $student->parent->phone ?? '-' }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface space-y-1">
                                    @forelse ($student->classes as $class)
                                        @if ($class->pivot->is_active)
                                            @php
                                                $total = $class->program->total_sessions;
                                                $left = $total === null ? null : max(0, $total - $class->pivot->sessions_completed);
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <span class="font-body-sm text-body-sm text-on-surface">{{ $class->name }}</span>
                                                <span class="font-body-sm text-body-sm text-outline">{{ $class->pivot->sessions_completed }}/{{ $total ?? '-' }}</span>
                                                @if ($class->pivot->renewal_status === 'lanjut')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E6F8FC] text-secondary">Lanjut</span>
                                                @elseif ($left === null)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Bulanan</span>
                                                @elseif ($left === 0)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Paket habis</span>
                                                @elseif ($left <= 1)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">Sisa {{ $left }}x</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Sisa {{ $left }}x</span>
                                                @endif
                                            </div>
                                        @endif
                                    @empty
                                        <span class="font-body-sm text-body-sm text-outline">-</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.students.show', $student) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Lihat Rekap</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30">{{ $students->links() }}</div>
        </div>
    </div>
</x-sidebar-layout>
