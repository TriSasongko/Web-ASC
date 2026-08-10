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

        <div x-data="{ tambahJadwal: false }">
            <button @click="tambahJadwal = ! tambahJadwal" type="button"
                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Jadwal
            </button>

            <div x-show="tambahJadwal" x-transition class="mt-4 bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
                <form action="{{ route('admin.schedules.store') }}" method="POST" x-data="{ classId: '', students: {{ Js::from($studentsByClass) }} }" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div class="md:col-span-2">
                        <x-input-label for="class_id" value="Kelas" />
                        <select id="class_id" name="class_id" x-model="classId" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->level_label ?? '-' }} — {{ $c->program?->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="day" value="Hari" />
                        <select id="day" name="day" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                            <option value="">-- Hari --</option>
                            @foreach (\App\Models\ClassSchedule::DAYS as $day)
                                <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="session_number" value="Sesi" />
                        <x-text-input id="session_number" type="number" name="session_number" placeholder="Sesi ke-" min="1" value="1" required />
                    </div>
                    <div>
                        <x-input-label for="start_time" value="Mulai" />
                        <x-text-input id="start_time" type="time" name="start_time" required />
                    </div>
                    <div>
                        <x-input-label for="end_time" value="Selesai" />
                        <x-text-input id="end_time" type="time" name="end_time" required />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="location" value="Lokasi (opsional)" />
                        <x-text-input id="location" type="text" name="location" placeholder="Lokasi (opsional)" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="coach_ids" value="Pelatih (bisa lebih dari satu)" />
                        <div class="max-h-48 overflow-y-auto space-y-1.5 border border-outline-variant/50 rounded-lg p-3">
                            @forelse ($coaches as $c)
                                <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low px-2 py-1.5 rounded">
                                    <input type="checkbox" name="coach_ids[]" value="{{ $c->id }}" class="rounded border-outline-variant text-primary focus:ring-primary/40">
                                    {{ $c->name }}
                                </label>
                            @empty
                                <p class="font-body-sm text-body-sm text-outline px-1">Belum ada pelatih aktif.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label value="Siswa kelas yang ikut sesi ini" />
                        <p class="font-body-sm text-body-sm text-outline mb-2">Centang siswa kelas tersebut yang bisa latihan di hari & jam ini.</p>
                        <div class="max-h-48 overflow-y-auto space-y-1.5 border border-outline-variant/50 rounded-lg p-3">
                            <template x-for="st in students" :key="st.id">
                                <template x-if="st.class_id == classId">
                                    <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low px-2 py-1.5 rounded">
                                        <input type="checkbox" name="student_ids[]" :value="st.id" class="rounded border-outline-variant text-primary focus:ring-primary/40">
                                        <span x-text="st.name"></span>
                                    </label>
                                </template>
                            </template>
                            <p x-show="classId == ''" class="font-body-sm text-body-sm text-outline px-1">Pilih kelas terlebih dahulu untuk melihat daftar siswanya.</p>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex items-center gap-2 bg-secondary-container/30 text-secondary px-4 py-3 rounded-lg font-body-sm text-body-sm">
            <span class="material-symbols-outlined text-[18px]">info</span>
            Klik <strong>Atur</strong> pada sebuah sesi untuk menugaskan pelatih dan siswa. Kelola kelas (tambah/ubah kelas, penempatan siswa) tetap di menu <a href="{{ route('admin.classes.index') }}" class="underline font-bold">Kelas</a>.
        </div>

        @include('admin.schedules._grid', ['schedulesByDay' => $schedulesByDay, 'manageable' => true, 'coaches' => $coaches])
    </div>
</x-sidebar-layout>
