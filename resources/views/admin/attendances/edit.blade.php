<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Koreksi Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Perbaiki tanggal pencatatan absensi siswa.</p>
            </div>
            <a href="{{ route('admin.attendances.history', $attendance->class_id) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-xl">
            <div class="flex items-center gap-3 mb-6 p-4 rounded-lg bg-surface-container-low">
                <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-outline">Siswa</p>
                    <p class="font-label-md text-label-md text-on-surface">{{ $attendance->student->full_name }}</p>
                </div>
            </div>

            <form action="{{ route('admin.attendances.update', $attendance) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="attendance_date" value="Tanggal Pertemuan" />
                    <x-text-input id="attendance_date" type="date" name="attendance_date"
                                  value="{{ old('attendance_date', $attendance->attendance_date->format('Y-m-d')) }}" required />
                    <x-input-error :messages="$errors->get('attendance_date')" class="mt-2" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>
                    <a href="{{ route('admin.attendances.history', $attendance->class_id) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
