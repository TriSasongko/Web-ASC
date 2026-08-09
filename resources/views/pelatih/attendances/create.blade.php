<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Ambil Absensi</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">{{ $class->name }} — centang siswa yang mengikuti latihan pada tanggal tersebut.</p>
            </div>
            <a href="{{ route('pelatih.attendances.history', $class) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">history</span>
                Riwayat
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-3xl">
            <form action="{{ route('pelatih.attendances.store', $class) }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input-label for="attendance_date" value="Tanggal Latihan" />
                        <x-text-input id="attendance_date" type="date" name="attendance_date" class="mt-1 block w-full"
                                      value="{{ old('attendance_date', now()->format('Y-m-d')) }}" required />
                        <x-input-error :messages="$errors->get('attendance_date')" class="mt-2" />
                    </div>
                    {{-- <div>
                        <x-input-label for="session_number" value="Sesi Ke-" />
                        <x-text-input id="session_number" type="number" min="1" name="session_number" class="mt-1 block w-full"
                                      value="{{ old('session_number', 1) }}" required />
                        <x-input-error :messages="$errors->get('session_number')" class="mt-2" />
                    </div> --}}
                </div>

                <div class="border-t border-outline-variant/30 pt-6">
                    <h3 class="font-headline text-headline-sm text-on-surface mb-1">Daftar Siswa</h3>
                    <p class="font-body-sm text-body-sm text-outline mb-4">Centang siswa yang mengikuti latihan pada tanggal tersebut.</p>

                    <div class="space-y-3">
                        @forelse ($students as $student)
                            <div class="flex items-center justify-between gap-4 p-4 rounded-lg border border-outline-variant/30 hover:bg-surface-container-low/50 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-full bg-surface-container text-on-surface-variant flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">person</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-label-md text-label-md text-on-surface truncate">{{ $student->full_name }}</p>
                                        <p class="font-body-sm text-body-sm text-outline">
                                            Pertemuan terhitung: {{ $student->pivot->sessions_completed }}/{{ $class->program->total_sessions ?? '∞' }}
                                        </p>
                                    </div>
                                </div>
                                <label class="cursor-pointer shrink-0">
                                    <input type="checkbox" name="attendance[]" value="{{ $student->id }}" checked class="peer sr-only">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full border border-outline-variant/50 text-on-surface-variant font-label-sm text-label-sm transition peer-checked:text-white peer-checked:border-[#2E7D32] peer-checked:bg-[#2E7D32] peer-focus-visible:ring-2 peer-focus-visible:ring-primary/50">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                        Hadir
                                    </span>
                                </label>
                            </div>
                        @empty
                            <div class="p-6 text-center rounded-lg border border-dashed border-outline-variant/50">
                                <p class="font-body-sm text-body-sm text-outline">Belum ada siswa aktif di kelas ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan Absensi</x-primary-button>
                    <a href="{{ route('pelatih.attendances.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
