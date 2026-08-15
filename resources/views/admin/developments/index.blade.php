<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Perkembangan Siswa — Semua</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Daftar seluruh penilaian perkembangan siswa di semua kelas.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex flex-col sm:flex-row sm:items-center gap-4 sm:justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">insights</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Data Perkembangan</h3>
                </div>
                <form method="GET" class="flex items-center gap-2 w-full">
                    <div class="relative flex-1 min-w-0">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." class="w-full pl-10 bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                    </div>
                    <button type="submit" class="shrink-0 inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <span class="sm:hidden">Cari</span>
                    </button>
                </form>
            </div>

            {{-- Mobile: kartu siswa --}}
            <div class="md:hidden divide-y divide-outline-variant/30">
                @forelse ($students as $student)
                    @php
                        $devs = $student->developments;
                        $latest = $devs->first();
                    @endphp
                    <div class="p-4 hover:bg-surface-container-low/50 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-label-md text-label-md text-on-surface truncate">{{ $student->full_name }}</p>
                                <p class="font-body-sm text-body-sm text-outline truncate mt-0.5">{{ $latest?->schoolClass?->name ?? '-' }}</p>
                            </div>
                            <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px]">groups</span>
                                {{ $latest?->coach?->name ?? '-' }}
                            </span>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2">
                            <a href="{{ route('admin.classes.developments.history', [$latest->schoolClass, $student]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                <span class="material-symbols-outlined text-[16px]">insights</span>
                                Lihat Perkembangan
                            </a>
                            <a href="{{ route('eraport.show', [$student, $latest->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                <span class="material-symbols-outlined text-[16px]">description</span>
                                Lihat E-Raport
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center font-body-sm text-body-sm text-outline">Belum ada data.</div>
                @endforelse
            </div>

            {{-- Desktop: tabel siswa --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kelas</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Coach</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($students as $student)
                            @php
                                $devs = $student->developments;
                                $latest = $devs->first();
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $student->full_name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $latest?->schoolClass?->name ?? '-' }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $latest?->coach?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('admin.classes.developments.history', [$latest->schoolClass, $student]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                            <span class="material-symbols-outlined text-[16px]">insights</span>
                                            Lihat Perkembangan
                                        </a>
                                        <a href="{{ route('eraport.show', [$student, $latest->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                            <span class="material-symbols-outlined text-[16px]">description</span>
                                            Lihat E-Raport
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center font-body-sm text-body-sm text-outline">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30">{{ $students->links() }}</div>
        </div>
    </div>
</x-sidebar-layout>
