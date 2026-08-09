<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Riwayat Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Rekap kehadiran per siswa. Cari nama murid atau filter rentang tanggal.</p>
            </div>
            <a href="{{ route('pelatih.attendances.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Ambil Absensi
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <h3 class="font-headline text-headline-sm text-on-surface">Rekap Absensi</h3>
            </div>

            <div class="p-5 border-b border-outline-variant/30">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="student_name" value="{{ request('student_name') }}" placeholder="Cari nama siswa..."
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                    </div>
                    <div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                        <button type="submit" class="shrink-0 inline-flex items-center justify-center gap-2 bg-primary text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Tanggal</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Lokasi</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Nama Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Kelas</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Level</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($attendances as $a)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $a->attendance_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $a->location ?? '-' }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $a->student->full_name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $a->schoolClass?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-primary-container text-on-primary">{{ $a->schoolClass?->level_label ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $a->recorder->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center font-body-sm text-body-sm text-outline">Belum ada riwayat absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <label for="per_page" class="font-body-sm text-body-sm text-on-surface-variant whitespace-nowrap">Tampilkan</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-1.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                        @foreach ([5, 10, 25, 50] as $p)
                            <option value="{{ $p }}" {{ request('per_page', 10) == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    <span class="font-body-sm text-body-sm text-on-surface-variant whitespace-nowrap">per halaman</span>
                    @foreach (['student_name', 'date_from', 'date_to'] as $filter)
                        @if (request($filter))
                            <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                        @endif
                    @endforeach
                </form>
                <div>{{ $attendances->links() }}</div>
            </div>
        </div>

        <a href="{{ route('pelatih.attendances.index') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
    </div>
</x-sidebar-layout>
