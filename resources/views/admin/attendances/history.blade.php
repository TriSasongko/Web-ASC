<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Riwayat Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Rekap kehadiran per siswa. Cari nama murid atau filter rentang tanggal.</p>
            </div>
            <a href="{{ route('admin.attendances.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Input Absensi
            </a>
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
                    <span class="material-symbols-outlined text-primary text-[20px]">history</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Rekap Absensi</h3>
                </div>
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

            {{-- Mobile: kartu absensi --}}
            <div class="md:hidden divide-y divide-outline-variant/30">
                @forelse ($attendances as $a)
                    <div class="p-4 hover:bg-surface-container-low/50 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $a->student->full_name }}</p>
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container text-on-primary">{{ $a->schoolClass?->level_label ?? '-' }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-outline truncate mt-0.5">{{ $a->schoolClass?->name ?? '-' }}</p>
                            </div>
                            <span class="shrink-0 font-label-sm text-label-sm text-on-surface whitespace-nowrap">{{ $a->attendance_date->format('d-m-Y') }}</span>
                        </div>
                        <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 font-body-sm text-body-sm text-outline">
                            @if ($a->location)
                                <span class="inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">place</span>
                                    {{ $a->location }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">person</span>
                                {{ $a->recorder->name }}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center gap-4">
                            <a href="{{ route('admin.attendances.edit', $a) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                Edit
                            </a>
                            <form action="{{ route('admin.attendances.destroy', $a) }}" method="POST" class="inline"
                                  onsubmit="return confirmDeleteAttendance(event, this, '{{ $a->student->full_name }}', '{{ $a->attendance_date->format('d-m-Y') }}', '{{ $a->schoolClass?->name ?? '' }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-error font-label-md text-label-md hover:underline">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center font-body-sm text-body-sm text-outline">Belum ada riwayat absensi.</div>
                @endforelse
            </div>

            {{-- Desktop: tabel absensi --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lokasi</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kelas</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Level</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Dicatat Oleh</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
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
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('admin.attendances.edit', $a) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.attendances.destroy', $a) }}" method="POST" class="inline"
                                              onsubmit="return confirmDeleteAttendance(event, this, '{{ $a->student->full_name }}', '{{ $a->attendance_date->format('d-m-Y') }}', '{{ $a->schoolClass?->name ?? '' }}')">
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
                                <td colspan="7" class="px-4 py-8 text-center font-body-sm text-body-sm text-outline">Belum ada riwayat absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30">{{ $attendances->links() }}</div>
        </div>
    </div>
</x-sidebar-layout>
