<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Dashboard Pelatih</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola absensi dan perkembangan siswa kelas Anda.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8">
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <div class="p-3 bg-primary-container text-on-primary rounded-xl shrink-0">
                    <span class="material-symbols-outlined">sports</span>
                </div>
                <div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Selamat datang, {{ auth()->user()->name }}!</h3>
                    <p class="font-body-sm text-body-sm text-outline mt-1">Anda login sebagai Pelatih. Gunakan menu Absensi untuk mencatat kehadiran dan Perkembangan untuk mengisi penilaian siswa.</p>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
