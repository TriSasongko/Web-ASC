@props([
    'action',
    'cancel',
    'submitLabel' => 'Simpan Absensi',
    'classes' => collect(),
    'students' => collect(),
    'attendanceByDate' => [],
    'blockedStudentIds' => [],
])

<form action="{{ $action }}" method="POST" class="space-y-6"
      x-data="{ attendanceDate: @js(old('attendance_date', now()->format('Y-m-d'))), search: '', classId: '', recorded: @js($attendanceByDate), blocked: @js($blockedStudentIds) }">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="attendance_date" value="Tanggal Latihan" />
            <x-text-input id="attendance_date" type="date" name="attendance_date" class="mt-1 block w-full"
                          x-model="attendanceDate" value="{{ old('attendance_date', now()->format('Y-m-d')) }}" required />
            <x-input-error :messages="$errors->get('attendance_date')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="location" value="Lokasi" />
            <x-text-input id="location" name="location" class="mt-1 block w-full"
                          value="{{ old('location') }}" placeholder="Contoh: Lapangan ASC" />
            <x-input-error :messages="$errors->get('location')" class="mt-2" />
        </div>
    </div>

    <div class="border-t border-outline-variant/30 pt-6">
        <div class="flex items-center gap-2 mb-1">
            <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
            <h3 class="font-headline text-headline-sm text-on-surface">Daftar Siswa</h3>
            <span class="ml-auto inline-flex items-center gap-1 font-body-sm text-body-sm text-outline"
                  x-show="(recorded[attendanceDate] || []).length > 0" x-cloak>
                <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
                <span x-text="(recorded[attendanceDate] || []).length + ' siswa sudah di absen pada tanggal ini'"></span>
            </span>
        </div>
        <p class="font-body-sm text-body-sm text-outline mb-4">Setiap siswa hanya dapat diabsensi sekali per hari. Siswa yang sudah tercatat hadir pada tanggal terpilih otomatis dinonaktifkan.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <select x-model="classId" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                    <option value="">-- Semua Kelas --</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->level_label }} · {{ $class->program->name }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input x-model="search" type="text" placeholder="Cari nama murid..." class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-outline-variant/30">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider w-full">Nama Siswa</th>
                        <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider hidden sm:table-cell">Kelas · Level</th>
                        <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Pertemuan</th>
                        <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-right">Hadir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse ($students as $student)
                        @php
                            $activeClasses = $student->classes;
                            $primary = $activeClasses->first();
                        @endphp
                        <tr class="hover:bg-surface-container-low/50 transition-colors"
                            data-name="{{ mb_strtolower($student->full_name) }}"
                            data-classes="{{ $activeClasses->pluck('id')->implode(',') }}"
                            x-show="(classId === '' || $el.dataset.classes.split(',').includes(classId)) && (search === '' || $el.dataset.name.includes(search.toLowerCase()))">
                            <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface truncate">{{ $student->full_name }}</td>
                            <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface truncate hidden sm:table-cell">
                                @forelse ($activeClasses as $class)
                                    {{ $class->name }} · {{ $class->level_label }}{{ ! $loop->last ? ', ' : '' }}
                                @empty
                                    <span class="text-outline">Tanpa kelas aktif</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-2 font-body-sm text-body-sm text-outline whitespace-nowrap">
                                {{ $primary ? $primary->pivot->sessions_completed.'/'.($primary->program->total_sessions ?? '∞') : '-' }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                <template x-if="blocked.includes({{ $student->id }})">
                                    <span class="inline-flex items-center gap-1 font-body-sm text-body-sm text-orange whitespace-nowrap">
                                        <span class="material-symbols-outlined text-[16px]">event_busy</span> Paket habis
                                    </span>
                                </template>
                                <template x-if="!blocked.includes({{ $student->id }}) && (recorded[attendanceDate] || []).includes({{ $student->id }})">
                                    <span class="inline-flex items-center gap-1 font-body-sm text-body-sm text-secondary whitespace-nowrap">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Sudah di absen
                                    </span>
                                </template>
                                <template x-if="!blocked.includes({{ $student->id }}) && !(recorded[attendanceDate] || []).includes({{ $student->id }})">
                                    <label class="cursor-pointer inline-flex items-center gap-1.5">
                                        <input type="checkbox" name="attendance[]" value="{{ $student->id }}" class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary/40">
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">Hadir</span>
                                    </label>
                                </template>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center font-body-sm text-body-sm text-outline">Belum ada siswa dengan kelas aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ $cancel }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">Batal</a>
    </div>
</form>
