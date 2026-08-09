<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Catat kehadiran siswa dan lihat rekap absensi.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.attendances.create') }}" class="group bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 transition-all hover:border-primary/40 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-primary-container text-on-primary rounded-xl shrink-0">
                        <span class="material-symbols-outlined text-[24px]">fact_check</span>
                    </div>
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Input Absensi</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-1">Semua siswa langsung tampil — pilih tanggal, cari nama murid, lalu centang siswa yang hadir.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.attendances.history') }}" class="group bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 transition-all hover:border-primary/40 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-secondary-container/30 text-secondary rounded-xl shrink-0">
                        <span class="material-symbols-outlined text-[24px]">history</span>
                    </div>
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Riwayat Absensi</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-1">Rekap kehadiran per siswa, cari berdasarkan nama atau rentang tanggal.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-primary-container text-on-primary rounded-xl shrink-0">
                    <span class="material-symbols-outlined">event_available</span>
                </div>
                <div>
                    <h3 class="font-headline text-headline-sm text-on-surface">{{ $recordedCount }} data absensi</h3>
                    <p class="font-body-sm text-body-sm text-outline mt-1">Total kehadiran siswa yang tercatat. Data ini juga menjadi dasar perhitungan gaji coach.</p>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
