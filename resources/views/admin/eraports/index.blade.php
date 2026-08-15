<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">E-Raport</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Lihat dan unduh rekap penilaian perkembangan siswa.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <form method="GET" class="flex items-center gap-2 w-full">
                    <div class="relative flex-1 min-w-0">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant/50 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary font-body-sm text-body-sm transition-all">
                    </div>
                    <button type="submit" class="shrink-0 inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2 rounded-full font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <span class="sm:hidden">Cari</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kelas</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Level</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Program</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Periode Terakhir</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($students as $student)
                            @forelse ($student->classes as $class)
                                @php
                                    $latest = $student->developments
                                        ->where('class_id', $class->id)
                                        ->sortByDesc('created_at')
                                        ->first();
                                @endphp
                                <tr class="hover:bg-surface-container-low/50 transition-colors">
                                    <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $student->full_name }}</td>
                                    <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $class->name }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container/60 text-on-primary">{{ $class->level_label ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $class->program->name }}</td>
                                    <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">
                                        @if ($latest)
                                            {{ $latest->period }}
                                        @else
                                            <span class="font-body-sm text-body-sm text-outline">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($latest)
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('eraport.show', [$student, $latest->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                                    Lihat
                                                </a>
                                                <a href="{{ route('eraport.pdf', [$student, $latest->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline" title="Unduh PDF">
                                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                                    Unduh
                                                </a>
                                            </div>
                                        @else
                                            <span class="font-body-sm text-body-sm text-outline">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 font-body-sm text-body-sm text-outline">{{ $student->full_name }} (belum ada kelas aktif)</td>
                                </tr>
                            @endforelse
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <label for="per_page" class="font-body-sm text-body-sm text-on-surface-variant whitespace-nowrap">Tampilkan</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-1.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                        @foreach ([5, 10, 25, 50] as $p)
                            <option value="{{ $p }}" {{ request('per_page', 10) == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    <span class="font-body-sm text-body-sm text-on-surface-variant whitespace-nowrap">per halaman</span>
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>
                <div class="w-full">{{ $students->links('vendor.pagination.prevnext') }}</div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
