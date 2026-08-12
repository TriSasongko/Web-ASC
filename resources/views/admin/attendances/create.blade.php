<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Input Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Tandai siswa yang mengikuti latihan pada tanggal tersebut. Absensi tercatat per siswa, tidak terikat kelas.</p>
            </div>
            <a href="{{ route('admin.attendances.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-4xl">
            <x-attendance-student-form
                :action="route('admin.attendances.store')"
                :cancel="route('admin.attendances.index')"
                :classes="$classes"
                :students="$students"
                :attendanceByDate="$attendanceByDate" />
        </div>
    </div>
</x-sidebar-layout>
