<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelas Saya — ambil absensi, isi perkembangan, atau lihat riwayat.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <h3 class="font-headline text-headline-sm text-on-surface">Kelas Saya</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Nama Kelas</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Program</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Jumlah Siswa Aktif</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($classes as $class)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->program->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E6F8FC] text-secondary">
                                        {{ $class->students()->wherePivot('is_active', true)->count() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('pelatih.attendances.create', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Ambil Absensi</a>
                                        <a href="{{ route('pelatih.developments.index', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Perkembangan</a>
                                        <a href="{{ route('pelatih.attendances.history', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Riwayat</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center font-body-sm text-body-sm text-outline">Anda belum memiliki kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-sidebar-layout>
