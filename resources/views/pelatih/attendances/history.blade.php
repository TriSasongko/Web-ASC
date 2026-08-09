<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Riwayat Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">{{ $class->name }} — daftar absensi yang telah dicatat.</p>
            </div>
            <a href="{{ route('pelatih.attendances.create', $class) }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Ambil Absensi
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <h3 class="font-headline text-headline-sm text-on-surface">Riwayat {{ $class->name }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Tanggal</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Sesi</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Nama Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Level</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($attendances as $a)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $a->attendance_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">Sesi {{ $a->session_number }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $a->student->full_name }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-primary-container text-on-primary">{{ $a->schoolClass?->level_label ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center font-body-sm text-body-sm text-outline">Belum ada riwayat absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-outline-variant/30">{{ $attendances->links() }}</div>
        </div>

        <a href="{{ route('pelatih.attendances.index') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
    </div>
</x-sidebar-layout>
