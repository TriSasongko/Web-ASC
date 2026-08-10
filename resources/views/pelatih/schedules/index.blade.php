<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Jadwal Latihan Saya</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Sesi latihan yang Anda ampu, dari Senin sampai Minggu — lengkap dengan jam, lokasi, dan siswa.</p>
            </div>
        </div>

        @include('admin.schedules._grid', ['schedulesByDay' => $schedulesByDay, 'showClassLink' => false])
    </div>
</x-sidebar-layout>
