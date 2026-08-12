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

        @if (session('error'))
            <div class="flex items-center gap-2 bg-[#FFEBEE] text-[#C62828] border border-[#C62828]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">error</span>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-2 bg-[#FFEBEE] text-[#C62828] border border-[#C62828]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
            </dl>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">calendar_month</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Jadwal Latihan</h3>
                </div>
                <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                    <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                    Kelola di Halaman Jadwal
                </a>
            </div>
            @forelse ($class->schedules as $s)
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 rounded-lg border border-outline-variant/30 bg-surface-container-low/40 px-4 py-3 mb-2">
                    <span class="inline-flex items-center gap-1.5 font-body-sm text-body-sm text-on-surface">
                        <span class="material-symbols-outlined text-[16px] text-outline">calendar_today</span>
                        {{ ucfirst($s->day) }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 font-body-sm text-body-sm text-on-surface">
                        <span class="material-symbols-outlined text-[16px] text-outline">schedule</span>
                        {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 font-body-sm text-body-sm text-on-surface">
                        <span class="material-symbols-outlined text-[16px] text-outline">local_activity</span>
                        Sesi {{ $s->session_number }}
                    </span>
                    @if ($s->location)
                        <span class="inline-flex items-center gap-1.5 font-body-sm text-body-sm text-on-surface">
                            <span class="material-symbols-outlined text-[16px] text-outline">location_on</span>
                            {{ $s->location }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-1.5 font-body-sm text-body-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px] text-outline">sports</span>
                        {{ $s->coaches->pluck('name')->join(', ') ?: '-' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 font-body-sm text-body-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px] text-outline">groups</span>
                        {{ $s->students->count() }} siswa
                    </span>
                </div>
            @empty
                <p class="font-body-sm text-body-sm text-outline">Belum ada jadwal. Tambahkan lewat halaman Jadwal.</p>
            @endforelse
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

                                        @if ($class->level !== null && $class->level < \App\Models\SchoolClass::LEVEL_ELITE && $candidateClasses->isNotEmpty())
                                            @if ($left === null || $left === 0)
                                        <div x-data="{ open: false }" class="relative inline-block">
                                            <button @click="open = true" type="button"
                                                class="inline-flex items-center gap-1 bg-[#E8F5E9] text-[#2E7D32] px-2.5 py-1 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">
                                                <span class="material-symbols-outlined text-[16px]">north_east</span>
                                                Naik Kelas
                                            </button>
                                            <div x-show="open" x-cloak x-transition.opacity
                                                class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
                                                @click="open = false"></div>
                                            <div x-show="open" x-cloak x-transition
                                                @keydown.escape.window="open = false"
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden">
                                                    <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50">
                                                        <div>
                                                            <h3 class="font-headline text-headline-sm text-on-surface">Ajukan Naik Kelas</h3>
                                                            <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $student->full_name }}</p>
                                                        </div>
                                                        <button @click="open = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                                                            <span class="material-symbols-outlined text-[20px]">close</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('admin.class-students.move', $e->id) }}" method="POST" class="px-6 py-5 space-y-4"
                                                          onsubmit="return confirm('Ajukan {{ $student->full_name }} ke kelas terpilih? Wajib konfirmasi ke orang tua sebelum siswa dipindahkan.')">
                                                        @csrf
                                                        <div>
                                                            <x-input-label for="target_class_id" value="Kelas Target" />
                                                            <select id="target_class_id" name="target_class_id" required class="mt-1 w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                                                <option value="">-- Pilih kelas target --</option>
                                                                @foreach ($candidateClasses as $c)
                                                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->level_label ?? '-' }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <p class="font-body-sm text-body-sm text-outline rounded-lg bg-surface-container-low/50 border border-outline-variant/30 px-3 py-2">Setelah diajukan, wajib konfirmasi ke orang tua via WhatsApp lalu selesaikan di menu Rekomendasi.</p>
                                                        <div class="flex items-center justify-end gap-2 pt-2">
                                                            <button @click="open = false" type="button" class="inline-flex items-center justify-center gap-2 border border-outline-variant/50 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                                                                Batal
                                                            </button>
                                                            <button type="submit" class="inline-flex items-center justify-center gap-2 bg-[#E8F5E9] text-[#2E7D32] px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                                                                <span class="material-symbols-outlined text-[18px]">north_east</span>
                                                                Ajukan Naik Kelas
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                            @else
                                                <span class="font-label-sm text-label-sm text-outline">Paket belum habis</span>
                                            @endif
                                        @endif

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