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
                @if ($errors->has('coach_ids') && is_array($errors->get('coach_ids')[0]))
                    <div class="mb-4 flex items-start gap-2 bg-[#FFEBEE] text-[#C62828] border border-[#C62828]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                        <span class="material-symbols-outlined text-[18px] mt-0.5">error</span>
                        <div>
                            <p class="font-semibold mb-1">Konflik jadwal ditemukan:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->get('coach_ids')[0] as $c)
                                    <li><strong>{{ $c['coach'] }}</strong> sudah mengajar <strong>{{ $c['class'] }}</strong> di <strong>{{ $c['day'] }}</strong> jam <strong>{{ $c['time'] }}</strong></li>
                                @endforeach
                            </ul>
                            <p class="mt-1">Tidak ada jadwal yang dibuat. Silakan ubah jam atau pelatih, lalu coba lagi.</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.schedules.store') }}" method="POST" x-data="{ classId: '', students: {{ Js::from($studentsByClass) }}, allDays: false, days: [], dayDropdownOpen: false, studentSearch: '', dayLabels: { senin: 'Senin', selasa: 'Selasa', rabu: 'Rabu', kamis: 'Kamis', jumat: 'Jumat', sabtu: 'Sabtu', minggu: 'Minggu' }, get dayText() { if (this.days.length === 0) return 'Pilih Hari'; if (this.days.length === 7) return 'Semua Hari'; return this.days.map(d => this.dayLabels[d]).join(', '); }, get filteredStudents() { const q = this.studentSearch.toLowerCase(); return this.students.filter(st => st.class_id == this.classId && (q === '' || st.name.toLowerCase().includes(q))); } }" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <div class="md:col-span-2">
                        <x-input-label value="Hari" />
                        <div class="relative mt-1" @click.outside="dayDropdownOpen = false">
                            <button type="button" @click="dayDropdownOpen = !dayDropdownOpen"
                                class="w-full flex items-center justify-between gap-2 bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all text-left">
                                <span x-text="dayText" :class="days.length === 0 ? 'text-outline' : ''"></span>
                                <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform" :class="dayDropdownOpen ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            <div x-show="dayDropdownOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                                class="absolute z-30 mt-1 w-full bg-surface-container-lowest border border-outline-variant/50 rounded-lg shadow-lg overflow-hidden">
                                <div class="p-2 space-y-0.5">
                                    <label class="flex items-center gap-2 px-3 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low rounded cursor-pointer transition-colors">
                                        <input type="checkbox" x-model="allDays" @change="
                                            const all = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
                                            days = allDays ? [...all] : [];
                                        " class="rounded border-outline-variant text-primary focus:ring-primary/40">
                                        Semua Hari
                                    </label>
                                    <div class="border-t border-outline-variant/30 my-1"></div>
                                    @foreach (\App\Models\ClassSchedule::DAYS as $day)
                                        <label class="flex items-center gap-2 px-3 py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low rounded cursor-pointer transition-colors">
                                            <input type="checkbox" name="days[]" value="{{ $day }}" x-model="days" @change="allDays = days.length === 7" class="rounded border-outline-variant text-primary focus:ring-primary/40">
                                            {{ ucfirst($day) }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <p x-show="days.length === 0" class="font-body-xs text-body-xs text-error mt-1">Pilih minimal satu hari.</p>
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
                        <div class="border border-outline-variant/50 rounded-lg overflow-hidden">
                            <div class="relative px-3 pt-3 pb-1">
                                <input type="text" x-model="studentSearch" placeholder="Cari nama siswa..." class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg pl-9 pr-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-[16px] text-outline">search</span>
                            </div>
                            <div class="max-h-48 overflow-y-auto space-y-1.5 p-3 pt-1">
                                <template x-for="st in filteredStudents" :key="st.id">
                                    <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low px-2 py-1.5 rounded">
                                        <input type="checkbox" name="student_ids[]" :value="st.id" class="rounded border-outline-variant text-primary focus:ring-primary/40">
                                        <span x-text="st.name"></span>
                                    </label>
                                </template>
                                <p x-show="filteredStudents.length === 0 && classId != ''" class="font-body-sm text-body-sm text-outline px-1">Tidak ada siswa yang cocok.</p>
                                <p x-show="classId == ''" class="font-body-sm text-body-sm text-outline px-1">Pilih kelas terlebih dahulu untuk melihat daftar siswanya.</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" @click="if (days.length === 0) { alert('Pilih minimal satu hari.'); $event.preventDefault(); }" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
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
