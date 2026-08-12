<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Ambil Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Catat kehadiran siswa pada tanggal latihan. Tidak terikat kelas — Anda bisa menggantikan coach mana pun.</p>
            </div>
            <a href="{{ route('pelatih.attendances.history') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">history</span>
                Riwayat
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-4xl">
            <x-attendance-student-form
                :action="route('pelatih.attendances.store')"
                :cancel="route('pelatih.attendances.index')"
                :classes="$classes"
                :students="$students"
                :attendanceByDate="$attendanceByDate" />
        </div>
    </div>
</x-sidebar-layout>
