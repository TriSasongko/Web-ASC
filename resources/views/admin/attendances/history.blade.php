<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Riwayat Absensi — {{ $class->name }}</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Daftar seluruh pencatatan absensi untuk kelas ini.</p>
            </div>
            <a href="{{ route('admin.attendances.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
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
                    <h3 class="font-headline text-headline-sm text-on-surface">Data Absensi</h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Sesi</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Level</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Dicatat Oleh</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
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
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $a->recorder->name }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('admin.attendances.edit', $a) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.attendances.destroy', $a) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus data absensi ini?')">
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
                                <td colspan="6" class="px-4 py-8 text-center font-body-sm text-body-sm text-outline">Belum ada riwayat absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30">{{ $attendances->links() }}</div>
        </div>
    </div>
</x-sidebar-layout>
