<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Manajemen Kelas</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola kelas, jadwal, dan penempatan siswa.</p>
            </div>
            <a href="{{ route('admin.classes.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Kelas
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
                <a href="{{ route('admin.class-students.unplaced') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                    Lihat siswa belum ditempatkan
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            {{-- Mobile: kartu kelas --}}
            <div class="md:hidden divide-y divide-outline-variant/30">
                @forelse ($classes as $class)
                    <div class="p-4 hover:bg-surface-container-low/50 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $class->name }}</p>
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container text-on-primary">{{ $class->level_label ?? '-' }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-outline truncate mt-0.5">{{ $class->program->name }}</p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="font-label-md text-label-md text-on-surface">{{ $class->students_count }}</p>
                                <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Murid</p>
                            </div>
                        </div>
                        <div class="mt-2 space-y-1">
                            @forelse ($class->schedules as $s)
                                <div class="flex items-center gap-1.5 font-body-sm text-body-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    {{ ucfirst($s->day) }}, {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} WIB
                                </div>
                            @empty
                                <span class="font-body-sm text-body-sm text-outline">Belum ada jadwal</span>
                            @endforelse
                        </div>
                        <div class="mt-3 flex items-center gap-4">
                            <a href="{{ route('admin.classes.show', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                Detail
                            </a>
                            <a href="{{ route('admin.classes.edit', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline"
                                  onsubmit="return confirmDeleteClass(event, this, '{{ $class->name }}', {{ $class->students_count }})">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-error font-label-md text-label-md hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center font-body-sm text-body-sm text-outline">Belum ada kelas.</div>
                @endforelse
            </div>

            {{-- Desktop: tabel kelas --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Kelas</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Level</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Program</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Jadwal</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Jumlah Murid</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($classes as $class)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->level_label ?? '-' }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->program->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">
                                    @forelse ($class->schedules as $s)
                                        <div>{{ ucfirst($s->day) }}, {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} WIB</div>
                                    @empty
                                        <span class="font-body-sm text-body-sm text-outline">Belum ada jadwal</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->students_count }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.classes.show', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Detail</a>
                                        <a href="{{ route('admin.classes.edit', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Edit</a>
                                        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline"
                                              onsubmit="return confirmDeleteClass(event, this, '{{ $class->name }}', {{ $class->students_count }})">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 text-error font-label-md text-label-md hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30">{{ $classes->links() }}</div>
        </div>
    </div>
</x-sidebar-layout>
