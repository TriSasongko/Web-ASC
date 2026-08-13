<x-sidebar-layout>
    @php $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.'); @endphp
    <div class="space-y-6">

        <!-- Header Title -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Ringkasan Statistik</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Selamat datang kembali! Berikut adalah ikhtisar operasional hari ini.</p>
            </div>
            <a href="{{ route('admin.registrations.index') }}"
                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-6 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm hover:shadow-md active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Tambah Peserta Baru
            </a>
        </div>

        <!-- KPI Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Total Siswa Aktif -->
            <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div class="p-3 bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined filled text-primary">group</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Total Siswa Aktif</p>
                    <h4 class="font-headline text-headline-xl text-on-surface">{{ number_format($totalStudents, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- Total Pelatih -->
            <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div class="p-3 bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined filled text-primary">sports</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Total Pelatih</p>
                    <h4 class="font-headline text-headline-xl text-on-surface">{{ number_format($totalCoaches, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- Pendaftaran Menunggu -->
            <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div class="p-3 bg-surface-container-low rounded-lg">
                        <span class="material-symbols-outlined filled text-primary">person_add</span>
                    </div>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 font-label-sm text-label-sm rounded-md">Perlu Review</span>
                </div>
                <div class="mt-4">
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Pendaftaran Menunggu</p>
                    <h4 class="font-headline text-headline-xl text-on-surface">{{ number_format($pendingRegistrations, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- Paket Perlu Konfirmasi -->
            <div class="bg-surface-container-lowest rounded-xl p-6 border border-error/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div class="p-3 bg-error/10 rounded-lg">
                        <span class="material-symbols-outlined filled text-error">inventory_2</span>
                    </div>
                    <span class="px-2 py-1 bg-error/10 text-error font-label-sm text-label-sm rounded-md">Urgent</span>
                </div>
                <div class="mt-4">
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Paket Perlu Konfirmasi</p>
                    <h4 class="font-headline text-headline-xl text-error">{{ number_format($needConfirmationCount, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <!-- Operational Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Jadwal Latihan Hari Ini -->
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                <div class="px-6 py-5 border-b border-outline-variant/30 bg-surface/50 flex justify-between items-center">
                    <h5 class="font-headline text-headline-sm text-on-surface">Jadwal Latihan Hari Ini</h5>
                    <a href="{{ route('admin.schedules.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">Kelola Jadwal</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Waktu</th>
                                <th class="px-6 py-3">Kelas</th>
                                <th class="px-6 py-3">Lokasi</th>
                                <th class="px-6 py-3">Pelatih</th>
                                <th class="px-6 py-3">Jumlah Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            @forelse ($todaySchedules as $s)
                                <tr class="hover:bg-surface-container-low/50 transition-colors">
                                    <td class="px-6 py-3 font-body-sm text-body-sm font-semibold text-on-surface whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('admin.classes.show', $s->schoolClass) }}" class="font-label-md text-label-md text-on-surface hover:text-primary">{{ $s->schoolClass?->name ?? '-' }}</a>
                                        <p class="font-body-sm text-body-sm text-outline">{{ $s->schoolClass?->program?->name }} · {{ $s->schoolClass?->level_label ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-3 font-body-sm text-body-sm text-on-surface">{{ $s->location ?? '-' }}</td>
                                    <td class="px-6 py-3">
                                        @forelse ($s->coaches as $c)
                                            <span class="inline-flex items-center px-2 py-1 bg-primary/10 text-primary font-label-sm text-label-sm rounded-md mr-1">{{ $c->name }}</span>
                                        @empty
                                            <span class="font-body-sm text-body-sm text-outline">-</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-3 font-body-sm text-body-sm text-on-surface">{{ $s->students->count() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center font-body-sm text-body-sm text-outline">Tidak ada jadwal hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Siswa Belum Ditempatkan -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] flex flex-col">
                <div class="px-6 py-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h5 class="font-headline text-headline-sm text-on-surface">Siswa Belum Ditempatkan</h5>
                        @if ($unplacedCount > 0)
                            <span class="inline-flex items-center justify-center min-w-6 px-2 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm">{{ $unplacedCount }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.schedules.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">Tempatkan Siswa</a>
                </div>
                <div class="p-6 flex-1 space-y-4">
                    @forelse ($unplacedStudents as $st)
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-tertiary-fixed text-tertiary flex items-center justify-center font-label-md text-label-md shrink-0">
                                    {{ strtoupper(substr($st->full_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $st->full_name }}</p>
                                    <p class="font-body-sm text-body-sm text-outline truncate">{{ $st->classes->pluck('name')->implode(', ') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.schedules.index') }}" class="text-primary font-label-sm text-label-sm hover:bg-primary/10 px-3 py-1.5 rounded-md transition-colors shrink-0">Tempatkan</a>
                        </div>
                    @empty
                        <p class="font-body-sm text-body-sm text-outline text-center py-4">Semua siswa sudah ditempatkan. 🎉</p>
                    @endforelse
                </div>
                @if ($unplacedCount > $unplacedStudents->count())
                    <div class="px-6 py-3 border-t border-outline-variant/30 text-center">
                        <span class="font-body-sm text-body-sm text-outline">+{{ $unplacedCount - $unplacedStudents->count() }} lainnya belum ditampilkan</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Analytics & Action Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5">
                        <h5 class="font-headline text-headline-sm text-on-surface mb-4">Tren Pertumbuhan Siswa</h5>
                        <div class="relative h-[200px] w-full">
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5">
                        <h5 class="font-headline text-headline-sm text-on-surface mb-4">Status Pendaftaran</h5>
                        <div class="relative h-[200px] w-full">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Konfirmasi Paket -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                        <h5 class="font-headline text-headline-sm text-on-surface">Menunggu Konfirmasi Paket</h5>
                        <a href="{{ route('admin.renewals.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">Kelola</a>
                    </div>
                    <div class="p-4 space-y-3">
                        @forelse ($alerts as $alert)
                            <div class="flex items-center justify-between gap-3 bg-surface-container-low p-3 rounded-lg border border-outline-variant/30">
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $alert->class_name }}</p>
                                    <p class="font-body-sm text-body-sm text-outline">{{ $alert->total }} siswa menunggu konfirmasi</p>
                                </div>
                                <a href="{{ route('admin.classes.show', $alert->class_id) }}"
                                    class="px-3 py-1.5 bg-primary-container text-on-primary rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-opacity shrink-0">Lihat Kelas</a>
                            </div>
                        @empty
                            <p class="font-body-sm text-body-sm text-outline text-center py-4">Tidak ada paket yang perlu dikonfirmasi. 🎉</p>
                        @endforelse
                    </div>
                </div>

                <!-- Distribusi Paket -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5">
                    <h5 class="font-headline text-headline-sm text-on-surface mb-4">Distribusi Paket</h5>
                    <div class="flex items-center justify-center gap-10 flex-wrap">
                        <div class="relative h-[150px] w-[150px] shrink-0">
                            <canvas id="packageChart"></canvas>
                        </div>
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-primary-container"></span>
                                <span class="font-label-sm text-label-sm text-outline">Aktif</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-[#FFB300]"></span>
                                <span class="font-label-sm text-label-sm text-outline">Hampir Habis</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-error"></span>
                                <span class="font-label-sm text-label-sm text-outline">Habis</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Area -->
            <div class="space-y-6">
                <!-- Aktivitas Terbaru -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5">
                    <h5 class="font-headline text-headline-sm text-on-surface mb-4">Aktivitas Terbaru</h5>
                    <div class="space-y-4">
                        @forelse ($activities as $activity)
                            <div class="relative pl-9">
                                <div class="absolute left-0 top-0.5 w-7 h-7 rounded-full {{ $activity['iconBg'] }} flex items-center justify-center ring-4 ring-surface-container-lowest">
                                    <span class="material-symbols-outlined text-[14px] {{ $activity['iconColor'] }}">{{ $activity['icon'] }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface">
                                    @if ($activity['subject'])
                                        <span class="font-label-md text-label-md">{{ $activity['subject'] }}</span>
                                    @endif
                                    {{ $activity['text'] }}
                                </p>
                                <p class="font-label-sm text-label-sm text-outline mt-0.5">{{ $activity['time']->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="font-body-sm text-body-sm text-outline">Belum ada aktivitas.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Honor Pelatih -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                    <div class="px-5 py-4 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">payments</span>
                            <h5 class="font-headline text-headline-sm text-on-surface">Honor Pelatih</h5>
                        </div>
                        <a href="{{ route('admin.salaries.index') }}" class="text-primary font-label-sm text-label-sm hover:underline shrink-0">Kelola Honor</a>
                    </div>

                    @if ($honorCoachCount === 0)
                        <p class="font-body-sm text-body-sm text-outline text-center py-8">Semua honor pelatih sudah dibayar. 🎉</p>
                    @else
                        <div class="px-5 py-3 border-b border-outline-variant/30 bg-surface-container-low/40 flex items-center justify-between gap-3">
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Belum dibayar · {{ $honorCoachCount }} pelatih</p>
                            <p class="font-label-md text-label-md text-error whitespace-nowrap">{{ $fmt($honorTotal) }}</p>
                        </div>
                        <div class="divide-y divide-outline-variant/30">
                            @foreach ($honorCoaches as $hc)
                                <div class="flex items-center justify-between gap-3 px-5 py-3">
                                    <div class="min-w-0">
                                        <p class="font-label-md text-label-md text-on-surface truncate">{{ $hc['name'] }}</p>
                                        <p class="font-body-sm text-body-sm text-outline">{{ $hc['sessions'] }} sesi belum dibayar</p>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0">
                                        <span class="font-label-md text-label-md text-on-surface whitespace-nowrap">{{ $fmt($hc['total']) }}</span>
                                        <form action="{{ route('admin.salaries.pay', $hc['id']) }}" method="POST"
                                              onsubmit="return confirm('Tandai honor {{ addslashes($hc['name']) }} sebesar {{ $fmt($hc['total']) }} ({{ $hc['sessions'] }} sesi) sebagai dibayar?')">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg text-primary hover:bg-primary/10 transition-colors" title="Tandai Dibayar">
                                                <span class="material-symbols-outlined text-[18px]">payments</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($honorCoachCount > $honorCoaches->count())
                            <div class="px-5 py-2.5 border-t border-outline-variant/30 text-center">
                                <span class="font-body-sm text-body-sm text-outline">+{{ $honorCoachCount - $honorCoaches->count() }} pelatih lainnya · lihat di Kelola Honor</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-6 text-center border-t border-outline-variant/50">
            <p class="font-body-sm text-body-sm text-outline">© {{ date('Y') }} ASC Academy. Seluruh hak cipta dilindungi.</p>
        </footer>
    </div>

    <!-- Chart Initializations -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const growthLabels = @json($growth->pluck('label'));
            const growthData = @json($growth->pluck('count'));
            const statusData = @json($statusData);
            const packageData = @json($packageData);

            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#737785';
            Chart.defaults.scale.grid.color = 'rgba(194, 198, 214, 0.2)';

            // 1. Line Chart (Tren Pertumbuhan)
            const growthCtx = document.getElementById('growthChart').getContext('2d');
            const gradient = growthCtx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(0, 71, 169, 0.15)');
            gradient.addColorStop(1, 'rgba(0, 71, 169, 0)');

            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: growthLabels,
                    datasets: [{
                        label: 'Total Siswa',
                        data: growthData,
                        borderColor: '#0047a9',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0047a9',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: true,
                        tension: 0.4
                    }]
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
                            ticks: { maxTicksLimit: 5, font: { size: 11 } },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } },
                            border: { display: false }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });

            // 2. Bar Chart (Status Pendaftaran)
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: ['Menunggu', 'Diterima', 'Ditolak'],
                    datasets: [{
                        data: [statusData.menunggu, statusData.diterima, statusData.ditolak],
                        backgroundColor: ['#FFB300', '#0047a9', '#ba1a1a'],
                        borderRadius: 6,
                        barThickness: 48
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#121B2E',
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { maxTicksLimit: 5, font: { size: 11 } },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } },
                            border: { display: false }
                        }
                    }
                }
            });

            // 3. Donut Chart (Distribusi Paket)
            const packageCtx = document.getElementById('packageChart').getContext('2d');
            new Chart(packageCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Aktif', 'Hampir Habis', 'Habis'],
                    datasets: [{
                        data: [packageData.aktif, packageData.hampir_habis, packageData.habis],
                        backgroundColor: ['#0047a9', '#FFB300', '#ba1a1a'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#121B2E',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function (context) {
                                    return ` ${context.label}: ${context.raw} siswa`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-sidebar-layout>
