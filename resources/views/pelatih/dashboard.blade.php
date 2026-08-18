<x-sidebar-layout>
    @php
        $chartLabels = $attendanceChart->pluck('label');
        $chartTotal = $attendanceChart->pluck('total');
        $chartStudents = $attendanceChart->pluck('students');
        $todayLabel = ucfirst($todayDay).', '.now()->format('d M Y');
    @endphp

    <div class="space-y-8">
        <!-- Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#0047a9] via-[#0b5ed7] to-secondary shadow-[0_10px_40px_rgba(11,94,215,0.25)] text-white p-6 md:p-8">
            <div class="absolute -right-10 -top-16 w-56 h-56 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute right-16 -bottom-20 w-48 h-48 bg-white/10 rounded-full blur-lg"></div>
            <div class="absolute -left-10 -bottom-16 w-40 h-40 bg-secondary-fixed/20 rounded-full blur-xl"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <p class="font-label-sm text-label-sm text-white/80 uppercase tracking-widest mb-2">{{ $todayLabel }}</p>
                    <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-white">Selamat datang kembali, {{ auth()->user()->name }} 👋</h2>
                    <p class="font-body-sm text-body-sm text-white/85 mt-2 max-w-xl">
                        Berikut ringkasan aktivitas latihan Anda hari ini — jadwal, absensi, dan penilaian dalam satu tempat.
                    </p>
                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-sm font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                            {{ $todaySchedules->count() }} Jadwal Hari Ini
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-sm font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-[16px]">group</span>
                            {{ $todayStudentCount }} Siswa
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-sm font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-[16px]">event_available</span>
                            {{ $totalAttendanceToday }} Absensi Dicatat
                        </span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                    <a href="{{ route('pelatih.attendances.create') }}" class="inline-flex items-center justify-center gap-2 bg-white text-[#0047a9] px-5 py-3 rounded-xl font-label-md text-label-md shadow-sm hover:opacity-90 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">event_available</span>
                        Ambil Absensi
                    </a>
                    @if ($canAssess)
                        <a href="{{ route('pelatih.developments.index') }}" class="inline-flex items-center justify-center gap-2 border border-white/50 text-white px-5 py-3 rounded-xl font-label-md text-label-md hover:bg-white/10 transition-all active:scale-95">
                            <span class="material-symbols-outlined text-[18px]">assessment</span>
                            Isi Penilaian
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-gradient-to-br from-[#0047a9] to-[#0b5ed7] rounded-xl p-5 text-white shadow-[0px_8px_24px_rgba(11,94,215,0.25)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                <div class="p-2.5 bg-white/20 rounded-lg w-fit relative z-10 mb-3">
                    <span class="material-symbols-outlined">event_available</span>
                </div>
                <p class="font-label-sm text-label-sm text-white/80 mb-1 uppercase tracking-wider relative z-10">Absensi Hari Ini</p>
                <h3 class="font-headline text-headline-xl relative z-10">{{ $totalAttendanceToday }}</h3>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-container/20 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                <div class="p-2.5 bg-primary-container text-on-primary rounded-lg w-fit relative z-10 mb-3">
                    <span class="material-symbols-outlined">fact_check</span>
                </div>
                <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider relative z-10">Total Absensi Dicatat</p>
                <h3 class="font-headline text-headline-xl text-on-surface relative z-10">{{ $totalAttendance }}</h3>
            </div>

            @if ($canAssess)
                <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-tertiary-fixed/60 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                    <div class="p-2.5 bg-tertiary-fixed text-on-tertiary-fixed rounded-lg w-fit relative z-10 mb-3">
                        <span class="material-symbols-outlined">assessment</span>
                    </div>
                    <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider relative z-10">Penilaian Diisi</p>
                    <h3 class="font-headline text-headline-xl text-on-surface relative z-10">{{ $totalDevelopments }}</h3>
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#FFF4E0] rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                    <div class="p-2.5 bg-[#FFF4E0] text-[#B26A00] rounded-lg w-fit relative z-10 mb-3">
                        <span class="material-symbols-outlined">north_east</span>
                    </div>
                    <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider relative z-10">Rekomendasi Diajukan</p>
                    <h3 class="font-headline text-headline-xl text-on-surface relative z-10">{{ $totalRecommendations }}</h3>
                </div>
            @else
                <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#FFF4E0] rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                    <div class="p-2.5 bg-[#FFF4E0] text-[#B26A00] rounded-lg w-fit relative z-10 mb-3">
                        <span class="material-symbols-outlined">north_east</span>
                    </div>
                    <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider relative z-10">Rekomendasi Diajukan</p>
                    <h3 class="font-headline text-headline-xl text-on-surface relative z-10">{{ $totalRecommendations }}</h3>
                </div>
                <div class="bg-surface-container-low rounded-xl p-5 border border-dashed border-outline-variant/50 flex items-center justify-center">
                    <p class="font-body-sm text-body-sm text-outline text-center">Izin mengisi penilaian belum aktif. Hubungi admin jika ingin mengaktifkannya.</p>
                </div>
            @endif
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 md:p-6">
                <div class="flex items-start justify-between gap-3 mb-5">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Jadwal Hari Ini</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $todayLabel }}</p>
                    </div>
                    <a href="{{ route('pelatih.schedules.index') }}" class="inline-flex items-center gap-1 text-primary font-label-sm text-label-sm hover:underline shrink-0">
                        Lihat Semua
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($todaySchedules as $schedule)
                        @php
                            $start = $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-';
                            $end = $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-';
                            $studentNames = $schedule->students->pluck('full_name');
                            $maxVisible = 6;
                            $visibleNames = $studentNames->take($maxVisible);
                            $overflowCount = max(0, $studentNames->count() - $maxVisible);
                        @endphp
                        <div class="border border-outline-variant/30 border-l-4 border-l-primary rounded-xl bg-surface/50 hover:bg-surface-container-low hover:border-primary/30 transition-all group">
                            <div class="p-4 md:p-5">
                                {{-- Baris 1: Waktu + Nama Kelas + Badge Siswa --}}
                                <div class="flex items-center gap-3 mb-3">
                                    {{-- Waktu --}}
                                    <div class="hidden sm:flex items-center gap-2 shrink-0">
                                        <span class="font-headline text-headline-sm text-primary">{{ $start }}</span>
                                        <span class="font-body-sm text-body-sm text-outline">–</span>
                                        <span class="font-headline text-headline-sm text-primary">{{ $end }}</span>
                                    </div>
                                    <div class="hidden sm:block w-px h-6 bg-outline-variant/30 shrink-0"></div>

                                    {{-- Nama Kelas --}}
                                    <div class="min-w-0 flex-1">
                                        <p class="font-label-md text-label-md text-on-surface truncate">{{ $schedule->schoolClass?->name ?? 'Tanpa Kelas' }}</p>
                                    </div>

                                    {{-- Badge Jumlah Siswa --}}
                                    <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary/10 text-primary font-label-sm text-label-sm">
                                        <span class="material-symbols-outlined text-[14px]">group</span>
                                        {{ $schedule->students->count() }}
                                    </span>
                                </div>

                                {{-- Waktu mobile --}}
                                <div class="flex sm:hidden items-center gap-2 mb-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary font-label-sm text-label-sm">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        {{ $start }} – {{ $end }}
                                    </span>
                                </div>

                                {{-- Baris 2: Info Tags --}}
                                <div class="flex flex-wrap items-center gap-1.5 mb-3">
                                    @if ($schedule->schoolClass)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[14px]">signal_cellular_alt</span>
                                            {{ $schedule->schoolClass->level_label }}
                                        </span>
                                    @endif
                                    @if ($schedule->schoolClass?->program)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[14px]">sports_soccer</span>
                                            {{ $schedule->schoolClass->program->name }}
                                        </span>
                                    @endif
                                    @if ($schedule->location)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                                            {{ $schedule->location }}
                                        </span>
                                    @endif
                                    @if ($schedule->session_number)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[14px]">tag</span>
                                            Sesi {{ $schedule->session_number }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Baris 3: Daftar Nama Siswa --}}
                                @if ($studentNames->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($visibleNames as $name)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-primary/5 text-on-surface border border-outline-variant/30 font-label-sm text-label-sm">
                                                {{ $name }}
                                            </span>
                                        @endforeach
                                        @if ($overflowCount > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-surface-container text-outline font-label-sm text-label-sm">
                                                +{{ $overflowCount }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <span class="material-symbols-outlined text-outline text-[40px]">event_busy</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2">Tidak ada jadwal latihan hari ini.</p>
                            <p class="font-body-sm text-body-sm text-outline mt-0.5">Nikmati hari santai atau siapkan materi latihan berikutnya!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        <!-- Jadwal Mendatang -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 md:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-headline text-headline-sm text-on-surface">Jadwal Mendatang</h3>
                <a href="{{ route('pelatih.schedules.index') }}" class="text-primary font-label-sm text-label-sm hover:underline shrink-0">Lihat Semua</a>
            </div>
            <div class="flex-1 space-y-3 overflow-y-auto max-h-[300px] pr-1">
                @forelse ($upcomingSchedules as $schedule)
                    @php
                        $sStart = $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-';
                        $sEnd = $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-';
                        $daysFromNow = (int) now()->startOfDay()->diffInDays($schedule->next_occurrence->startOfDay());
                        $dateLabel = $daysFromNow === 0 ? 'Hari ini' : 'Besok';
                    @endphp
                    <div class="flex items-center gap-3 border border-outline-variant/30 rounded-lg p-3">
                        <div class="p-2 bg-surface-container-low rounded-lg shrink-0">
                            <span class="material-symbols-outlined text-[18px] text-secondary">event_available</span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-label-md text-label-md text-on-surface truncate">{{ $schedule->schoolClass?->name ?? 'Tanpa Kelas' }}</p>
                            <p class="font-label-sm text-label-sm text-outline truncate">
                                {{ $sStart }} – {{ $sEnd }} · {{ $schedule->location ?? '-' }} · {{ $dateLabel }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <span class="material-symbols-outlined text-outline text-[32px]">event_busy</span>
                        <p class="font-body-sm text-body-sm text-outline mt-2">Tidak ada jadwal mendatang.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chart + Absensi Terakhir -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface mb-1">Aktivitas Absensi</h3>
                        <p class="font-body-sm text-body-sm text-outline">7 Hari Terakhir</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
                            <span class="w-3 h-3 rounded-full bg-[#0B5ED7]"></span>
                            Total Absensi
                        </span>
                        <span class="inline-flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
                            <span class="w-3 h-3 rounded-full bg-[#FFB300]"></span>
                            Siswa Unik
                        </span>
                    </div>
                </div>
                <div class="relative h-[260px] w-full">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-headline text-headline-sm text-on-surface">Absensi Terakhir</h3>
                    <a href="{{ route('pelatih.attendances.history') }}" class="text-primary font-label-sm text-label-sm hover:underline shrink-0">Lihat Semua</a>
                </div>
                <div class="flex-1 space-y-3 overflow-y-auto max-h-[300px] pr-1">
                    @forelse ($recentAttendances as $attendance)
                        <div class="flex items-center gap-3 border border-outline-variant/30 rounded-lg p-3">
                            <div class="p-2 bg-surface-container-low rounded-lg shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-secondary">event_available</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-label-md text-label-md text-on-surface truncate">{{ $attendance->student?->full_name ?? '-' }}</p>
                                <p class="font-label-sm text-label-sm text-outline truncate">
                                    {{ $attendance->schoolClass?->name ?? 'Tanpa Kelas' }} · {{ $attendance->attendance_date->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <span class="material-symbols-outlined text-outline text-[32px]">event_busy</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2">Belum ada absensi yang dicatat.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Catatan Pribadi -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 md:p-6">
            <div class="flex items-start justify-between gap-3 mb-5">
                <div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Catatan Pribadi</h3>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5">Catatan ini hanya dapat dilihat oleh Anda.</p>
                </div>
                <span class="p-2 bg-tertiary-fixed/60 text-tertiary rounded-lg shrink-0">
                    <span class="material-symbols-outlined">sticky_note_2</span>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <!-- Form Catatan -->
                <div class="md:col-span-2">
                    <form action="{{ route('pelatih.notes.store') }}" method="POST" class="bg-surface-container-low/50 border border-outline-variant/30 rounded-xl p-4">
                        @csrf
                        <div class="mb-3">
                            <x-input-label for="note_date" value="Tanggal Catatan" />
                            <input type="date" name="note_date" id="note_date" value="{{ old('note_date', now()->format('Y-m-d')) }}" required
                                class="mt-1 w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                            @error('note_date')
                                <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <textarea name="content" rows="4" placeholder="Tulis catatan Anda di sini..." required
                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all resize-none">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-end mt-3">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                                Simpan Catatan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Daftar Catatan -->
                <div class="md:col-span-3">
                    <div x-data="{ editing: null }" class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
                        @forelse ($notes as $note)
                            <div class="border border-outline-variant/30 rounded-lg p-4 bg-surface/50">
                                <template x-if="editing === {{ $note->id }}">
                                    <form action="{{ route('pelatih.notes.update', $note) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <div class="w-full sm:w-44 mb-3">
                                            <x-input-label for="note_date_{{ $note->id }}" value="Tanggal Catatan" />
                                            <input type="date" name="note_date" id="note_date_{{ $note->id }}" value="{{ $note->note_date?->format('Y-m-d') }}" required
                                                class="mt-1 w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                        </div>
                                        <textarea name="content" rows="2" required
                                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all resize-none">{{ $note->content }}</textarea>
                                        <div class="flex justify-end gap-2 mt-2">
                                            <button type="button" @click="editing = null" class="inline-flex items-center justify-center gap-2 border border-outline-variant/50 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">Batal</button>
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </template>
                                <template x-if="editing !== {{ $note->id }}">
                                    <div>
                                        <p class="font-body-sm text-body-sm text-on-surface whitespace-pre-wrap">{{ $note->content }}</p>
                                        <div class="flex items-center justify-between gap-2 mt-3">
                                            <p class="font-label-sm text-label-sm text-outline">{{ $note->note_date?->format('d M Y') ?? $note->created_at->format('d M Y') }}</p>
                                            <div class="flex items-center gap-1">
                                                <button type="button" @click="editing = {{ $note->id }}" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit Catatan">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </button>
                                                <div x-data="{ open: false }" class="relative inline-block">
                                                    <button @click="open = true" type="button" class="p-2 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus Catatan">
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </button>

                                                    <div x-show="open" x-cloak x-transition.opacity
                                                        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
                                                        @click="open = false"></div>

                                                    <div x-show="open" x-cloak x-transition
                                                        @keydown.escape.window="open = false"
                                                        class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                        <div class="w-full max-w-sm bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden">
                                                            <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50">
                                                                <div>
                                                                    <h3 class="font-headline text-headline-sm text-on-surface">Hapus Catatan</h3>
                                                                    <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $note->note_date?->format('d M Y') }}</p>
                                                                </div>
                                                                <button @click="open = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                                                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                                                </button>
                                                            </div>
                                                            <div class="px-6 py-5">
                                                                <p class="font-body-sm text-body-sm text-on-surface-variant">Yakin ingin menghapus catatan ini? Tindakan ini tidak dapat dibatalkan.</p>
                                                                <div class="flex items-center justify-end gap-2 mt-4">
                                                                    <button @click="open = false" type="button" class="inline-flex items-center justify-center gap-2 border border-outline-variant/50 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                                                                        Batal
                                                                    </button>
                                                                    <form action="{{ route('pelatih.notes.destroy', $note) }}" method="POST">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="inline-flex items-center justify-center gap-2 bg-error text-on-error px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                                                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                                                            Hapus
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @empty
                            <div class="text-center py-10 border border-dashed border-outline-variant/50 rounded-xl">
                                <span class="material-symbols-outlined text-outline text-[40px]">edit_note</span>
                                <p class="font-body-sm text-body-sm text-outline mt-2">Belum ada catatan. Tambahkan catatan pertama Anda di samping.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="h-8"></div>
    </div>

    <!-- Chart Initializations -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = @json($chartLabels);
            const totalData = @json($chartTotal);
            const studentsData = @json($chartStudents);

            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#737785';
            Chart.defaults.scale.grid.color = 'rgba(194, 198, 214, 0.2)';

            const ctx = document.getElementById('attendanceChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(11, 94, 215, 0.2)');
            gradient.addColorStop(1, 'rgba(11, 94, 215, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Absensi',
                            data: totalData,
                            borderColor: '#0B5ED7',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#0B5ED7',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Siswa Unik',
                            data: studentsData,
                            borderColor: '#FFB300',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#FFB300',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            fill: false,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#121B2E',
                            padding: 12,
                            titleFont: { size: 12, weight: '600' },
                            bodyFont: { size: 14, weight: '700' },
                            displayColors: false,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, maxTicksLimit: 6, font: { size: 11 } },
                            grid: { color: 'rgba(194, 198, 214, 0.2)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
</x-sidebar-layout>
