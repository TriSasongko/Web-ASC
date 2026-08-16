<x-sidebar-layout>
    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
        $dayLabels = ['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu', 'minggu' => 'Minggu'];
        $monthLabels = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $todayLabel = ucfirst($dayLabels[$todayDay]).', '.now()->format('d').' '.$monthLabels[now()->month].' '.now()->year;
    @endphp

    <div class="space-y-6">
        <!-- Hero Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#0047a9] via-[#0b5ed7] to-secondary shadow-[0_10px_40px_rgba(11,94,215,0.25)] text-white p-6 md:p-10">
            <div class="absolute -right-10 -top-16 w-56 h-56 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute right-16 -bottom-20 w-48 h-48 bg-white/10 rounded-full blur-lg"></div>
            <div class="absolute -left-10 -bottom-16 w-40 h-40 bg-secondary-fixed/20 rounded-full blur-xl"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <p class="font-label-sm text-label-sm text-white/80 uppercase tracking-widest mb-2">{{ $todayLabel }}</p>
                    <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-white">Halo, {{ auth()->user()->name }} 👋</h2>
                    <p class="font-body-sm text-body-sm text-white/85 mt-2 max-w-xl">
                        @if ($totalChildren > 0)
                            Pantau program, jadwal, dan perkembangan renang {{ $totalChildren > 1 ? $totalChildren.' anak' : 'anak' }} Anda di ASC Academy.
                        @else
                            Yuk daftarkan anak Anda dan mulailah perjalanan renangnya bersama ASC Academy.
                        @endif
                    </p>
                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-sm font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-[16px]">family_restroom</span>
                            {{ $totalChildren }} Anak
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-sm font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-[16px]">swim</span>
                            {{ $activePrograms }} Program Aktif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-sm font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-[16px]">event_available</span>
                            {{ $totalSessionsLeft }} Sesi Tersisa
                        </span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                    <a href="{{ route('orangtua.schedules.index') }}" class="inline-flex items-center justify-center gap-2 bg-white text-[#0047a9] px-5 py-3 rounded-xl font-label-md text-label-md shadow-sm hover:opacity-90 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                        Lihat Jadwal
                    </a>
                    <a href="{{ route('orangtua.eraports.index') }}" class="inline-flex items-center justify-center gap-2 border border-white/50 text-white px-5 py-3 rounded-xl font-label-md text-label-md hover:bg-white/10 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">description</span>
                        E-Raport
                    </a>
                </div>
            </div>
        </div>

        @if ($totalChildren > 0 && $activePrograms === 0)
            {{-- Modul status pendaftaran (anak terdaftar, belum ada paket aktif) --}}
            @php
                $regStatus = $latestRegistration?->status;
                $regBadge = match ($regStatus) {
                    'diterima' => 'bg-[#E8F5E9] text-[#2E7D32]',
                    'ditolak' => 'bg-error-container text-on-error-container',
                    default => 'bg-[#FFF8E1] text-[#B26A00]',
                };
                $regLabel = match ($regStatus) {
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu verifikasi',
                };
            @endphp
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                <div class="px-5 py-5 sm:px-6 border-b border-outline-variant/30 bg-surface/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-[#FFF8E1] text-[#B26A00] flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">hourglass_top</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-headline text-headline-sm text-on-surface">Pendaftaran Sedang Diproses</h3>
                            <p class="font-body-sm text-body-sm text-outline mt-0.5">Data anak Anda sudah dikirim. Tunggu verifikasi admin agar anak bisa mulai berlatih.</p>
                        </div>
                    </div>
                    <a href="{{ route('orangtua.registrations.index') }}"
                        class="shrink-0 inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                        <span class="material-symbols-outlined text-[18px]">list_alt</span>
                        Lihat Status Pendaftaran
                    </a>
                </div>

                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3 min-w-0">
                        @if ($latestRegistration)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full font-label-sm text-label-sm {{ $regBadge }}">
                                <span class="material-symbols-outlined text-[16px]">{{ $regStatus === 'ditolak' ? 'close' : ($regStatus === 'diterima' ? 'check_circle' : 'schedule') }}</span>
                                {{ $regLabel }}
                            </span>
                            <span class="font-body-sm text-body-sm text-on-surface truncate">{{ $latestRegistration->student?->full_name }} · {{ $latestRegistration->program?->name }}</span>
                        @else
                            <span class="font-body-sm text-body-sm text-on-surface">Belum ada pendaftaran terbaru.</span>
                        @endif
                    </div>

                    @if ($regStatus === 'ditolak')
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 shrink-0">
                            @if ($latestRegistration->rejection_reason)
                                <p class="font-body-sm text-body-sm text-error">{{ $latestRegistration->rejection_reason }}</p>
                            @endif
                            <a href="{{ route('orangtua.registrations.create') }}"
                                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all">
                                <span class="material-symbols-outlined text-[18px]">refresh</span>
                                Daftar Ulang
                            </a>
                        </div>
                    @else
                        <p class="font-body-sm text-body-sm text-outline shrink-0">Cek menu <span class="font-label-md text-label-md text-primary">Pendaftaran Anak Saya</span> untuk konfirmasi via WhatsApp.</p>
                    @endif
                </div>
            </div>
        @endif

        @if ($pendingRecommendations > 0)
            <div class="flex items-center gap-3 bg-[#E3F2FD] text-[#1565C0] border border-[#1565C0]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[20px]">forum</span>
                Anda memiliki {{ $pendingRecommendations }} rekomendasi naik kelas yang menunggu konfirmasi. Hubungi admin untuk konfirmasi.
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#E6F8FC] rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                <div class="p-2.5 bg-[#E6F8FC] text-secondary rounded-lg w-fit relative z-10 mb-3">
                    <span class="material-symbols-outlined">family_restroom</span>
                </div>
                <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider relative z-10">Jumlah Anak</p>
                <h3 class="font-headline text-headline-xl text-on-surface relative z-10">{{ $totalChildren }}</h3>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-container/20 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                <div class="p-2.5 bg-primary-container text-on-primary rounded-lg w-fit relative z-10 mb-3">
                    <span class="material-symbols-outlined">workspace_premium</span>
                </div>
                <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider relative z-10">Program Aktif</p>
                <h3 class="font-headline text-headline-xl text-on-surface relative z-10">{{ $activePrograms }}</h3>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#FFF4E0] rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                <div class="p-2.5 bg-[#FFF4E0] text-[#B26A00] rounded-lg w-fit relative z-10 mb-3">
                    <span class="material-symbols-outlined">event_available</span>
                </div>
                <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider relative z-10">Sisa Sesi Latihan</p>
                <h3 class="font-headline text-headline-xl text-on-surface relative z-10">{{ $totalSessionsLeft }}<span class="text-headline-sm text-outline">x</span></h3>
            </div>

            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-tertiary-fixed/60 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
                <div class="p-2.5 bg-tertiary-fixed text-tertiary rounded-lg w-fit relative z-10 mb-3">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider relative z-10">E-Raport Tersedia</p>
                <h3 class="font-headline text-headline-xl text-on-surface relative z-10">{{ $latestDevelopments->count() }}</h3>
            </div>
        </div>

        <!-- Jadwal Hari Ini + Program & Sisa Pertemuan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Jadwal Hari Ini -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 md:p-6">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Jadwal Latihan Hari Ini</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ ucfirst($todayDay) }}, {{ now()->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('orangtua.schedules.index') }}" class="inline-flex items-center gap-1 text-primary font-label-sm text-label-sm hover:underline shrink-0">
                        Jadwal Lengkap
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($todaySchedules as $schedule)
                        @php
                            $start = $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-';
                            $end = $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-';
                            $matching = $students->filter(fn ($s) => $s->classes->contains('id', $schedule->class_id));
                            $childNames = $matching->pluck('nickname')->filter()->implode(', ') ?: $matching->pluck('full_name')->implode(', ');
                        @endphp
                        <div class="flex items-start gap-3 border border-outline-variant/30 rounded-lg p-3 hover:border-primary/40 hover:bg-surface-container-low/50 transition-colors">
                            <div class="p-2.5 bg-primary-container/60 rounded-lg shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-on-primary">event</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $schedule->schoolClass?->name ?? 'Tanpa Kelas' }}</p>
                                    <span class="font-label-sm text-label-sm text-primary whitespace-nowrap">{{ $start }}–{{ $end }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">
                                    {{ $schedule->schoolClass?->program?->name ?? '-' }}
                                    @if ($schedule->schoolClass?->level)
                                        · {{ $schedule->schoolClass->level_label }}
                                    @endif
                                    @if ($schedule->location)
                                        · {{ $schedule->location }}
                                    @endif
                                </p>
                                @if ($childNames)
                                    <p class="font-label-sm text-label-sm text-outline mt-1">Untuk: {{ $childNames }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <span class="material-symbols-outlined text-outline text-[32px]">event_busy</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2">Tidak ada jadwal latihan hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Program & Sisa Pertemuan -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 md:p-6">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Program & Sisa Pertemuan</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">Paket latihan anak Anda saat ini</p>
                    </div>
                    <a href="{{ route('orangtua.schedules.index') }}" class="inline-flex items-center gap-1 text-primary font-label-sm text-label-sm hover:underline shrink-0">
                        Lihat Jadwal
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse ($students as $student)
                        <div class="rounded-xl border border-outline-variant/30 p-4 hover:bg-surface-container-low/50 transition-colors">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-tertiary-fixed text-tertiary flex items-center justify-center font-label-md text-label-md shrink-0">
                                    {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-headline text-headline-sm text-on-surface truncate">{{ $student->full_name }}</h4>
                                    <p class="font-label-sm text-label-sm text-outline">{{ $student->classes->count() }} program aktif</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                @forelse ($student->classes as $enrollment)
                                    @php
                                        $program = $enrollment->program;
                                        $total = $program->total_sessions;
                                        $left = $total === null ? null : max(0, $total - $enrollment->pivot->sessions_completed);
                                        $done = $total === null ? 0 : $enrollment->pivot->sessions_completed;
                                        $pct = $total === null ? null : min(100, (int) round(($done / $total) * 100));
                                        $barColor = $left === null ? 'bg-surface-container-highest' : ($left === 0 ? 'bg-error' : ($left <= 2 ? 'bg-[#FFB300]' : 'bg-primary'));
                                    @endphp
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                            <span class="font-label-md text-label-md text-on-surface">{{ $enrollment->name }} — {{ $program->name }}</span>
                                            @if ($left === null)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Bulanan</span>
                                            @elseif ($left === 0)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Paket habis</span>
                                            @elseif ($left <= 2)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">Sisa {{ $left }}x</span>
                                            @else
                                                <span class="font-label-sm text-label-sm text-outline">Sisa {{ $left }}x</span>
                                            @endif
                                            <span class="ml-auto font-label-sm text-label-sm text-outline">{{ $fmt($program->price) }}</span>
                                        </div>
                                        @if ($total !== null)
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 h-1.5 rounded-full bg-surface-container-highest/60 overflow-hidden">
                                                    <div class="h-full rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                                                </div>
                                                <span class="font-label-sm text-label-sm text-outline whitespace-nowrap">{{ $done }}/{{ $total }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="font-body-sm text-body-sm text-outline">Belum ada kelas aktif.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 border border-dashed border-outline-variant/50 rounded-lg">
                            <span class="material-symbols-outlined text-outline text-[40px]">child_care</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2">Belum ada anak terdaftar.</p>
                            <a href="{{ route('orangtua.registrations.create') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline mt-2">Daftarkan anak sekarang</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Grafik Absensi + Jadwal Mendatang -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 md:p-6">
                <div class="flex items-start justify-between gap-3 mb-1">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Perkembangan Penilaian Umum</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">Distribusi penilaian umum dari pelatih (periode terakhir)</p>
                    </div>
                    <span class="material-symbols-outlined text-outline text-[24px]">pie_chart</span>
                </div>
                @if ($totalChildren > 0)
                    <div class="grid grid-cols-1 {{ $totalChildren > 1 ? 'md:grid-cols-2' : '' }} gap-4 mt-5">
                        @forelse ($developmentCharts as $chart)
                            <div class="rounded-xl border border-outline-variant/30 p-4 hover:bg-surface-container-low/50 transition-colors">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-9 h-9 rounded-full bg-tertiary-fixed text-tertiary flex items-center justify-center font-label-md text-label-md shrink-0">
                                        {{ strtoupper(substr($chart['student_name'], 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-headline text-headline-sm text-on-surface truncate">{{ $chart['student_name'] }}</h4>
                                        <p class="font-label-sm text-label-sm text-outline truncate">
                                            @if ($chart['period'])
                                                {{ $chart['period'] }} · {{ $chart['total'] }} aspek dinilai
                                            @else
                                                Belum ada penilaian
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if ($chart['total'] > 0)
                                    <div class="flex flex-col sm:flex-row items-center gap-5">
                                        <div class="relative w-36 h-36 shrink-0">
                                            <canvas id="devChart-{{ $chart['student_id'] }}"></canvas>
                                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                                <span class="font-headline text-headline-md text-on-surface">{{ number_format($chart['average'], 1, ',', '.') }}</span>
                                                <span class="font-label-sm text-label-sm text-outline">rata-rata</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 w-full space-y-2">
                                            @foreach ($chart['slices'] as $slice)
                                                <div class="flex items-center gap-2">
                                                    <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $slice['color'] }}"></span>
                                                    <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $slice['label'] }}</span>
                                                    <span class="ml-auto font-label-sm text-label-sm text-on-surface tabular-nums">
                                                        {{ $slice['count'] }}<span class="text-outline"> · {{ round($slice['count'] / $chart['total'] * 100) }}%</span>
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <a href="{{ route('eraport.show', [$chart['student_id'], $chart['development_id']]) }}"
                                        class="mt-4 w-full inline-flex items-center justify-center gap-1.5 border border-primary/40 text-primary rounded-lg px-3 py-2 font-label-md text-label-md hover:bg-primary-container/50 transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">description</span>
                                        Detail E-Raport
                                    </a>
                                @else
                                    <div class="text-center py-8">
                                        <span class="material-symbols-outlined text-outline text-[32px]">assessment</span>
                                        <p class="font-body-sm text-body-sm text-outline mt-1">Belum ada penilaian perkembangan untuk anak ini.</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-10 lg:col-span-2">
                                <span class="material-symbols-outlined text-outline text-[40px]">assessment</span>
                                <p class="font-body-sm text-body-sm text-outline mt-2">Belum ada data penilaian perkembangan.</p>
                            </div>
                        @endforelse
                    </div>
                @else
                    <div class="text-center py-16">
                        <span class="material-symbols-outlined text-outline text-[40px]">assessment</span>
                        <p class="font-body-sm text-body-sm text-outline mt-2">Diagram penilaian muncul setelah anak terdaftar.</p>
                    </div>
                @endif
            </div>

            <!-- Jadwal Mendatang -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 md:p-6">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Jadwal Mendatang</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">7 hari ke depan</p>
                    </div>
                    <a href="{{ route('orangtua.schedules.index') }}" class="text-primary font-label-sm text-label-sm hover:underline shrink-0">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse ($upcomingSchedules as $item)
                        @php
                            $schedule = $item['schedule'];
                            $date = $item['date'];
                            $start = $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-';
                            $matching = $students->filter(fn ($s) => $s->classes->contains('id', $schedule->class_id));
                            $childNames = $matching->pluck('nickname')->filter()->implode(', ') ?: $matching->pluck('full_name')->implode(', ');
                            $isToday = $date->isToday();
                        @endphp
                        <div class="flex items-center gap-3 border border-outline-variant/30 rounded-lg p-3 {{ $isToday ? 'border-primary/50 bg-primary-container/10' : '' }}">
                            <div class="w-11 h-11 rounded-lg {{ $isToday ? 'bg-primary-container text-on-primary' : 'bg-surface-container-low text-on-surface-variant' }} flex flex-col items-center justify-center shrink-0">
                                <span class="font-headline text-headline-sm leading-none">{{ $date->format('d') }}</span>
                                <span class="font-label-sm text-label-sm uppercase leading-tight">{{ $date->format('M') }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-label-md text-label-md text-on-surface truncate">{{ $schedule->schoolClass?->name ?? 'Tanpa Kelas' }}</p>
                                <p class="font-body-sm text-body-sm text-outline truncate">
                                    {{ $dayLabels[$schedule->day] ?? ucfirst($schedule->day) }} {{ $date->format('d/m') }} · {{ $start }}
                                    @if ($childNames)
                                        · {{ $childNames }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-outline text-[32px]">event_upcoming</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2">Belum ada jadwal mendatang.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Rekomendasi + E-Raport -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Rekomendasi Naik Kelas -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
                <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Rekomendasi Naik Kelas</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">Pengajuan kenaikan level dari pelatih & admin</p>
                    </div>
                    <span class="material-symbols-outlined text-outline text-[24px]">north_east</span>
                </div>
                <div class="divide-y divide-outline-variant/30">
                    @forelse ($recommendations as $rec)
                        <div class="p-5 hover:bg-surface-container-low/50 transition-colors">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface">
                                        <span class="font-headline text-headline-sm">{{ $rec->student->full_name }}</span>
                                        @if ($rec->currentClass)
                                            — {{ $rec->currentClass->name }}
                                        @endif
                                        <span class="mx-1 text-outline">→</span>
                                        <span class="font-headline text-headline-sm text-primary">{{ $rec->recommendedClass->name ?? 'Level '.($rec->recommended_level ?? '-') }}</span>
                                    </p>
                                    @if ($rec->note)
                                        <p class="font-body-sm text-body-sm text-outline mt-1">Catatan: {{ $rec->note }}</p>
                                    @endif
                                </div>
                                <div class="shrink-0">
                                    @if ($rec->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#FFF8E1] text-[#B26A00] font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                                            Menunggu admin
                                        </span>
                                    @elseif ($rec->status === 'menunggu_ortu')
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#E3F2FD] text-[#1565C0] font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[16px]">forum</span>
                                            Menunggu konfirmasi Anda
                                        </span>
                                    @elseif ($rec->status === 'diterima')
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#E8F5E9] text-[#2E7D32] font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="p-6 text-center font-body-sm text-body-sm text-outline">Belum ada rekomendasi naik kelas.</p>
                    @endforelse
                </div>
            </div>

            <!-- E-Raport Terbaru -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
                <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">E-Raport Terbaru</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">Perkembangan terakhir setiap anak</p>
                    </div>
                    <a href="{{ route('orangtua.eraports.index') }}" class="inline-flex items-center gap-1 text-primary font-label-sm text-label-sm hover:underline shrink-0">
                        Semua E-Raport
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
                <div class="divide-y divide-outline-variant/30">
                    @forelse ($latestDevelopments as $dev)
                        <div class="p-5 flex items-center justify-between gap-4 hover:bg-surface-container-low/50 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-tertiary-fixed text-tertiary flex items-center justify-center font-label-md text-label-md shrink-0">
                                    {{ strtoupper(substr($dev->student->full_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $dev->student->full_name }}</p>
                                    <p class="font-label-sm text-label-sm text-outline truncate">Periode {{ $dev->period }}</p>
                                </div>
                            </div>
                            <a href="{{ route('eraport.show', [$dev->student, $dev->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline shrink-0">
                                Lihat
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    @empty
                        <div class="p-6 text-center">
                            <span class="material-symbols-outlined text-outline text-[32px]">description</span>
                            <p class="font-body-sm text-body-sm text-outline mt-2">Belum ada E-Raport tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Initializations -->
    @if ($totalChildren > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const charts = @json($developmentCharts);

                Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
                Chart.defaults.color = '#737785';

                charts.forEach((chart) => {
                    if (chart.total === 0) return;

                    const canvas = document.getElementById('devChart-' + chart.student_id);
                    if (!canvas) return;

                    new Chart(canvas.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: chart.slices.map((s) => s.label),
                            datasets: [{
                                data: chart.slices.map((s) => s.count),
                                backgroundColor: chart.slices.map((s) => s.color),
                                borderColor: '#FFFFFF',
                                borderWidth: 3,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '68%',
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#121B2E',
                                    padding: 12,
                                    titleFont: { size: 12, weight: '600' },
                                    bodyFont: { size: 14, weight: '700' },
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function (context) {
                                            const pct = Math.round(context.raw / chart.total * 100);
                                            return ' ' + context.raw + ' aspek (' + pct + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            });
        </script>
    @endif

    @if ($totalChildren === 0)
        {{-- Popup panduan pendaftaran untuk orang tua baru (muncul setiap login) --}}
        @php
            $dismissOnboarding = "open = false";
        @endphp
        <div x-data="{ open: true }">
            <div x-show="open" x-cloak x-transition.opacity
                class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
                @click="{{ $dismissOnboarding }}"></div>

            <div x-show="open" x-cloak x-transition
                @keydown.escape.window="open = false"
                class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="w-full max-w-lg bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden max-h-[88vh] flex flex-col">
                    {{-- Header merah --}}
                    <div class="relative bg-error text-on-error px-6 py-5 overflow-hidden">
                        {{-- <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full"></div>
                        <div class="absolute -right-2 -top-10 w-20 h-20 bg-white/10 rounded-full"></div> --}}
                        <div class="flex items-start gap-4 relative">
                            <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[26px]">how_to_reg</span>
                            </div>
                            <div class="min-w-0 pr-8">
                                <h3 class="font-headline text-headline-sm">Lengkapi Pendaftaran Anak Anda</h3>
                                <p class="font-body-sm text-body-sm text-on-error/85 mt-1">Agar anak Anda bisa segera mulai berlatih renang, lengkapi data dan pilih paket latihan.</p>
                            </div>
                            <button @click="{{ $dismissOnboarding }}" type="button"
                                class="absolute top-3 right-3 inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-white/15 transition-colors" title="Tutup">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                            </button>
                        </div>
                    </div>

                    {{-- Langkah panduan --}}
                    <div class="px-6 py-5 overflow-y-auto space-y-4">
                        @foreach ([
                            [1, 'Isi Data Anak', 'Lengkapi nama, tempat & tanggal lahir, jenis kelamin, berat dan tinggi badan anak Anda.'],
                            [2, 'Pilih Program & Paket', 'Pilih program latihan yang sesuai beserta paketnya (per pertemuan atau per bulan), lengkap dengan rincian biaya.'],
                            [3, 'Kirim Pendaftaran', 'Data masuk ke admin untuk diverifikasi. Pantau statusnya di menu Pendaftaran Anak Saya.'],
                            [4, 'Konfirmasi via WhatsApp', 'Admin akan menghubungi Anda. Bisa juga mengonfirmasi lewat WhatsApp agar proses lebih cepat.'],
                        ] as [$step, $title, $desc])
                            <div class="flex items-start gap-3">
                                <span class="w-8 h-8 rounded-full bg-error-container text-on-error-container flex items-center justify-center font-label-md text-label-md shrink-0">{{ $step }}</span>
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface">{{ $title }}</p>
                                    <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $desc }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer aksi --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-t border-outline-variant/30 bg-surface/50">
                        <a href="{{ route('orangtua.registrations.create') }}" @click="{{ $dismissOnboarding }}"
                            class="inline-flex items-center justify-center gap-2 bg-error text-on-error px-5 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">person_add</span>
                            Daftarkan Anak Sekarang
                        </a>
                        <button @click="{{ $dismissOnboarding }}" type="button"
                            class="inline-flex items-center justify-center gap-2 border border-error text-error px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-error-container transition-all">
                            Nanti Saja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('orangtua._confirmation_popup', ['regs' => $registrations->where('status', 'menunggu_verifikasi')->values()])
</x-sidebar-layout>
