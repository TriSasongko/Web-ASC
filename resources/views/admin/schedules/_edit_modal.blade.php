@props(['s' => null, 'classes' => [], 'coaches' => [], 'studentsByClass' => []])

@php
    $editStudentsByClass = collect($s->students->pluck('id'))->map(fn ($id) => (string) $id)->values()->toArray();
@endphp

<div x-show="open" x-cloak x-transition.opacity
    class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
    @click="open = false"></div>

<div x-show="open" x-cloak x-transition
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex justify-center p-4 items-start md:items-center overflow-y-auto">
    <div class="w-full max-w-2xl bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl flex flex-col max-h-[90vh] my-4 md:my-0">

        <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50 shrink-0">
            <div>
                <h3 class="font-headline text-headline-sm text-on-surface">Edit Jadwal</h3>
                <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ ucfirst($s->day) }} {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }} — {{ $s->schoolClass?->name }}</p>
            </div>
            <button @click="open = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        @if ($errors->has('coach_ids') && is_array($errors->get('coach_ids')[0]))
            <div class="mx-6 mt-4 flex items-start gap-2 bg-[#FFEBEE] text-[#C62828] border border-[#C62828]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm shrink-0">
                <span class="material-symbols-outlined text-[18px] mt-0.5">error</span>
                <div>
                    <p class="font-semibold mb-1">Konflik jadwal:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->get('coach_ids')[0] as $c)
                            <li><strong>{{ $c['coach'] }}</strong> sudah mengajar <strong>{{ $c['class'] }}</strong> di <strong>{{ $c['day'] }}</strong> jam <strong>{{ $c['time'] }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.schedules.update', $s) }}" method="POST"
            x-data="{
                classId: '{{ $s->class_id }}',
                students: {{ Js::from($studentsByClass) }},
                selectedIds: {{ Js::from($editStudentsByClass) }},
                studentSearch: '',
                get filteredStudents() {
                    const q = this.studentSearch.toLowerCase();
                    return this.students.filter(st => st.class_id == this.classId && (q === '' || st.name.toLowerCase().includes(q)));
                }
            }" class="flex flex-col min-h-0 flex-1">

            <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
                @csrf @method('PUT')
                <div>
                    <x-input-label for="edit_class_id_{{ $s->id }}" value="Kelas" />
                    <select id="edit_class_id_{{ $s->id }}" name="class_id" x-model="classId" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ $c->id === $s->class_id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->level_label ?? '-' }} — {{ $c->program?->name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="edit_day_{{ $s->id }}" value="Hari" />
                    <select id="edit_day_{{ $s->id }}" name="day" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                        @foreach (\App\Models\ClassSchedule::DAYS as $day)
                            <option value="{{ $day }}" {{ $day === $s->day ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_session_number_{{ $s->id }}" value="Sesi" />
                        <x-text-input id="edit_session_number_{{ $s->id }}" type="number" name="session_number" placeholder="Sesi ke-" min="1" :value="$s->session_number" required />
                    </div>
                    <div></div>
                    <div>
                        <x-input-label for="edit_start_time_{{ $s->id }}" value="Mulai" />
                        <x-text-input id="edit_start_time_{{ $s->id }}" type="time" name="start_time" :value="$s->start_time" required />
                    </div>
                    <div>
                        <x-input-label for="edit_end_time_{{ $s->id }}" value="Selesai" />
                        <x-text-input id="edit_end_time_{{ $s->id }}" type="time" name="end_time" :value="$s->end_time" required />
                    </div>
                </div>
                <div>
                    <x-input-label for="edit_location_{{ $s->id }}" value="Lokasi (opsional)" />
                    <x-text-input id="edit_location_{{ $s->id }}" type="text" name="location" placeholder="Lokasi (opsional)" :value="$s->location" />
                </div>
                <div>
                    <x-input-label for="edit_coach_ids_{{ $s->id }}" value="Pelatih (bisa lebih dari satu)" />
                    <div class="max-h-48 overflow-y-auto space-y-1.5 border border-outline-variant/50 rounded-lg p-3">
                        @forelse ($coaches as $c)
                            <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low px-2 py-1.5 rounded">
                                <input type="checkbox" name="coach_ids[]" value="{{ $c->id }}" @checked($s->coaches->contains($c->id)) class="rounded border-outline-variant text-primary focus:ring-primary/40">
                                {{ $c->name }}
                            </label>
                        @empty
                            <p class="font-body-sm text-body-sm text-outline px-1">Belum ada pelatih aktif.</p>
                        @endforelse
                    </div>
                </div>
                <div>
                    <x-input-label value="Siswa kelas yang ikut sesi ini" />
                    <div class="border border-outline-variant/50 rounded-lg overflow-hidden">
                        <div class="relative px-3 pt-3 pb-1">
                            <input type="text" x-model="studentSearch" placeholder="Cari nama siswa..." class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg pl-9 pr-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-[16px] text-outline">search</span>
                        </div>
                        <div class="max-h-48 overflow-y-auto space-y-1.5 p-3 pt-1">
                            <template x-for="st in filteredStudents" :key="st.id">
                                <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low px-2 py-1.5 rounded cursor-pointer">
                                    <input type="checkbox" :value="st.id" x-model="selectedIds" class="rounded border-outline-variant text-primary focus:ring-primary/40">
                                    <span x-text="st.name"></span>
                                </label>
                            </template>
                            <p x-show="filteredStudents.length === 0 && classId != ''" class="font-body-sm text-body-sm text-outline px-1">Tidak ada siswa yang cocok.</p>
                            <template x-for="id in selectedIds" :key="'sel-'+id">
                                <input type="hidden" name="student_ids[]" :value="id">
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-outline-variant/30 bg-surface/30 shrink-0">
                <button type="button" @click="open = false"
                    class="inline-flex items-center justify-center gap-2 bg-surface-container text-on-surface-variant px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-surface-container-high transition-all active:scale-95">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
