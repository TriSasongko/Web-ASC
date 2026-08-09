<x-sidebar-layout>
    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
        $remaining = fn ($e) => $e->remainingSessions();
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">{{ $class->name }}</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Detail kelas, jadwal, dan siswa.</p>
            </div>
            <a href="{{ route('admin.classes.edit', $class) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Edit Kelas
            </a>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-primary">info</span>
                <h3 class="font-headline text-headline-sm text-on-surface">Informasi Kelas</h3>
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">Program</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $class->program->name }} ({{ $fmt($class->program->price) }})</dd>
                </div>
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">Level</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $class->level_label ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">Coach</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $class->coach->name }}</dd>
                </div>
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">Kapasitas</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $enrollments->count() }}/{{ $class->capacity ?? '∞' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-primary">calendar_month</span>
                <h3 class="font-headline text-headline-sm text-on-surface">Jadwal Latihan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left mb-5">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Hari</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Jam</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Sesi</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lokasi</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($class->schedules as $s)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ ucfirst($s->day) }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">Sesi {{ $s->session_number }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $s->location ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('admin.schedules.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-error font-label-md text-label-md hover:underline">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center font-body-sm text-body-sm text-outline">Belum ada jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <form action="{{ route('admin.classes.schedules.store', $class) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-outline-variant/30 pt-5">
                @csrf
                <div>
                    <x-input-label for="day" value="Hari" />
                    <select id="day" name="day" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                        <option value="">-- Hari --</option>
                        @foreach (['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $day)
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
                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Jadwal
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">groups</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Siswa di Kelas Ini</h3>
                </div>
                <a href="{{ route('admin.classes.developments.index', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Perkembangan Siswa</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Paket + Harga</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Pertemuan</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status Paket</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($enrollments as $e)
                            @php
                                $student = $e->student;
                                $program = $e->schoolClass->program;
                                $left = $remaining($e);
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $student->full_name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $program->name }}<br><span class="font-body-sm text-body-sm text-outline">{{ $fmt($program->price) }}</span></td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $e->sessions_completed }}/{{ $program->total_sessions ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        @if ($left === null)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Bulanan</span>
                                        @elseif ($left >= 2)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Aman (sisa {{ $left }}x)</span>
                                        @elseif ($left === 1)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">Sisa 1 pertemuan</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Paket habis</span>
                                        @endif
                                        @if ($e->renewal_status === 'lanjut')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E6F8FC] text-secondary">Lanjut</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        @if ($left !== null && $left <= 1)
                                            <a href="{{ route('admin.students.show', $student) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Kelola Paket</a>
                                        @endif

                                        <div x-data="{ open: false }" class="relative inline-block">
                                            <button @click="open = ! open" type="button"
                                                class="inline-flex items-center justify-center gap-1.5 bg-[#FFF3E0] text-[#E65100] px-3 py-1.5 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">
                                                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                                Rekomendasi
                                            </button>
                                            <div x-show="open" @click.outside="open = false" x-transition
                                                class="absolute right-0 z-20 mt-2 w-80 bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-[0px_16px_48px_rgba(23,32,51,0.16)] p-5">
                                                <p class="font-label-md text-label-md text-on-surface mb-3">Rekomendasi Naik Kelas — {{ $student->full_name }}</p>
                                                <form action="{{ route('admin.recommendations.store') }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="current_class_id" value="{{ $class->id }}">
                                                    <div>
                                                        <x-input-label for="recommended_class_id" value="Kelas Target (opsional)" />
                                                        <select id="recommended_class_id" name="recommended_class_id" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                                            <option value="">-- Kelas target (opsional) --</option>
                                                            @foreach ($candidateClasses as $c)
                                                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->level_label ?? '-' }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="recommended_level" value="Level Target (opsional)" />
                                                        <select id="recommended_level" name="recommended_level" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                                            <option value="">-- Level target (opsional) --</option>
                                                            @foreach (\App\Models\SchoolClass::levelOptions() as $levelValue => $levelLabel)
                                                                <option value="{{ $levelValue }}">{{ $levelLabel }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="recommendation_note" value="Catatan (opsional)" />
                                                        <textarea id="recommendation_note" name="note" rows="2" placeholder="Catatan (opsional)" class="w-full border-outline-variant rounded-lg px-3 py-2 bg-surface-container-lowest shadow-sm focus:border-primary focus:ring-primary/30 font-body-sm text-body-sm"></textarea>
                                                    </div>
                                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[#FFF3E0] text-[#E65100] px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">Simpan Rekomendasi</button>
                                                </form>
                                            </div>
                                        </div>

                                        <a href="{{ route('admin.classes.developments.history', [$class, $student]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">Perkembangan</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali
        </a>
    </div>
</x-sidebar-layout>
