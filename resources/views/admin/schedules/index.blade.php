<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Jadwal Latihan</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola semua sesi latihan per hari, dari Senin sampai Minggu — jam, lokasi, pelatih, dan siswa.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div x-data="{ open: false }">
            <button @click="open = true" type="button"
                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Jadwal
            </button>

            @include('admin.schedules._create_modal', ['classes' => $classes, 'coaches' => $coaches, 'studentsByClass' => $studentsByClass])
        </div>

        <div class="flex items-center gap-2 bg-secondary-container/30 text-secondary px-4 py-3 rounded-lg font-body-sm text-body-sm">
            <span class="material-symbols-outlined text-[18px]">info</span>
            Klik <strong>Edit</strong> pada sebuah sesi untuk mengubah jadwal, pelatih, dan siswa. Kelola kelas (tambah/ubah kelas, penempatan siswa) tetap di menu <a href="{{ route('admin.classes.index') }}" class="underline font-bold">Kelas</a>.
        </div>

        @include('admin.schedules._grid', ['schedulesByDay' => $schedulesByDay, 'manageable' => true, 'coaches' => $coaches, 'classes' => $classes, 'studentsByClass' => $studentsByClass])
    </div>
</x-sidebar-layout>
