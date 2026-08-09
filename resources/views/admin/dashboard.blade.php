<x-sidebar-layout>
    @php
        $growthLast = $growth->last()['count'] ?? 0;
        $growthPrev = $growth->count() >= 2 ? $growth[$growth->count() - 2]['count'] : 0;
        $growthTrend = $growthPrev > 0 ? round(($growthLast - $growthPrev) / $growthPrev * 100) : null;
    @endphp

    <!-- Dashboard Content -->
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Selamat datang kembali, {{ auth()->user()->name }} 👋</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Berikut adalah ringkasan aktivitas ASC Academy hari ini.</p>
            </div>
            <a href="{{ route('admin.registrations.index') }}"
                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-6 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm hover:shadow-md active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Kelola Pendaftaran
            </a>
        </div>

        <!-- Stats Grid (Bento Style) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Stat 1: Total Siswa Aktif -->
            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] hover:shadow-[0px_4px_20px_rgba(23,32,51,0.08)] transition-shadow relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-surface-container-low rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2.5 bg-[#E6F8FC] text-secondary rounded-lg">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    @if ($growthTrend !== null)
                        <span class="inline-flex items-center gap-1 text-secondary font-label-sm text-label-sm bg-[#E6F8FC] px-2 py-1 rounded-full">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span>
                            +{{ $growthTrend }}%
                        </span>
                    @else
                        <span class="inline-flex items-center text-outline font-label-sm text-label-sm bg-surface-container px-2 py-1 rounded-full">Aktif</span>
                    @endif
                </div>
                <div class="relative z-10">
                    <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider">Total Siswa Aktif</p>
                    <h3 class="font-headline text-headline-xl text-on-surface">{{ number_format($totalStudents, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Stat 2: Total Pelatih -->
            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] hover:shadow-[0px_4px_20px_rgba(23,32,51,0.08)] transition-shadow relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-surface-container-low rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2.5 bg-tertiary-fixed text-on-tertiary-fixed rounded-lg">
                        <span class="material-symbols-outlined">sports</span>
                    </div>
                    <span class="inline-flex items-center text-outline font-label-sm text-label-sm bg-surface-container px-2 py-1 rounded-full">Semua Aktif</span>
                </div>
                <div class="relative z-10">
                    <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider">Total Pelatih</p>
                    <h3 class="font-headline text-headline-xl text-on-surface">{{ number_format($totalCoaches, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Stat 3: Pendaftaran Menunggu -->
            <div class="bg-surface-container-lowest rounded-xl p-5 border border-error/20 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] hover:shadow-[0px_4px_20px_rgba(23,32,51,0.08)] transition-shadow relative overflow-hidden group ring-1 ring-error/10">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-error-container/30 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2.5 bg-error-container text-on-error-container rounded-lg">
                        <span class="material-symbols-outlined">how_to_reg</span>
                    </div>
                    <span class="inline-flex items-center gap-1 text-error font-label-sm text-label-sm bg-error-container/50 px-2 py-1 rounded-full animate-pulse">
                        <span class="material-symbols-outlined text-[14px]">warning</span>
                        Perlu Review
                    </span>
                </div>
                <div class="relative z-10">
                    <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider">Pendaftaran Menunggu</p>
                    <h3 class="font-headline text-headline-xl text-error">{{ number_format($pendingRegistrations, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Stat 4: Paket Perlu Konfirmasi -->
            <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] hover:shadow-[0px_4px_20px_rgba(23,32,51,0.08)] transition-shadow relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-surface-container-low rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2.5 bg-[#FFF3E0] text-[#E65100] rounded-lg">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <span class="inline-flex items-center text-outline font-label-sm text-label-sm bg-surface-container px-2 py-1 rounded-full">Perlu Konfirmasi</span>
                </div>
                <div class="relative z-10">
                    <p class="font-label-sm text-label-sm text-outline mb-1 uppercase tracking-wider">Paket Perlu Konfirmasi</p>
                    <h3 class="font-headline text-headline-xl text-on-surface">{{ number_format($needConfirmationCount, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Action Items: Package Confirmation -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                    <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-error">notification_important</span>
                            <h3 class="font-headline text-headline-sm text-on-surface">Action Items: Paket Perlu Konfirmasi</h3>
                        </div>
                        <a href="{{ route('admin.classes.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">View All</a>
                    </div>
                    <div class="divide-y divide-outline-variant/30">
                        @forelse ($alerts as $alert)
                            <div class="p-4 flex items-center justify-between hover:bg-surface-container-low/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-[#E6F8FC] flex items-center justify-center text-secondary">
                                        <span class="material-symbols-outlined">pool</span>
                                    </div>
                                    <div>
                                        <h4 class="font-label-md text-label-md text-on-surface">{{ $alert->class_name }}</h4>
                                        <p class="font-body-sm text-body-sm text-outline">{{ $alert->coach_name }} • {{ $alert->total }} siswa menunggu konfirmasi</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.classes.show', $alert->class_id) }}"
                                    class="px-4 py-1.5 border border-primary text-primary rounded-lg font-label-sm text-label-sm hover:bg-primary-container hover:text-on-primary transition-colors">
                                    Lihat Kelas
                                </a>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <p class="font-body-sm text-body-sm text-outline">Tidak ada paket yang perlu dikonfirmasi. 🎉</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Line Chart: Growth -->
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5">
                        <h3 class="font-headline text-headline-sm text-on-surface mb-1">Tren Pertumbuhan Siswa</h3>
                        <p class="font-body-sm text-body-sm text-outline mb-4">6 Bulan Terakhir</p>
                        <div class="relative h-[220px] w-full">
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>
                    <!-- Bar Chart: Registration Status -->
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5">
                        <h3 class="font-headline text-headline-sm text-on-surface mb-1">Status Pendaftaran</h3>
                        <p class="font-body-sm text-body-sm text-outline mb-4">Bulan Ini</p>
                        <div class="relative h-[220px] w-full">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column (1/3) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Activity Timeline -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 h-[400px] flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-headline text-headline-sm text-on-surface">Aktivitas Terbaru</h3>
                        <button class="p-1 text-outline hover:text-primary transition-colors rounded">
                            <span class="material-symbols-outlined text-[20px]">refresh</span>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto pr-2 relative">
                        <!-- Timeline Line -->
                        <div class="absolute left-[15px] top-2 bottom-2 w-px bg-outline-variant/40"></div>
                        <div class="space-y-6">
                            @forelse ($activities as $activity)
                                <div class="relative pl-10">
                                    <div class="absolute left-0 top-1 w-8 h-8 rounded-full {{ $activity['iconBg'] }} flex items-center justify-center ring-4 ring-surface-container-lowest">
                                        <span class="material-symbols-outlined text-[16px] {{ $activity['iconColor'] }}">{{ $activity['icon'] }}</span>
                                    </div>
                                    <p class="font-body-sm text-body-sm text-on-surface">
                                        @if ($activity['subject'])
                                            <span class="font-label-md text-label-md">{{ $activity['subject'] }}</span>
                                        @endif
                                        {{ $activity['text'] }}
                                    </p>
                                    <p class="font-label-sm text-label-sm text-outline mt-1">{{ $activity['time']->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="font-body-sm text-body-sm text-outline">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-outline-variant/30 text-center">
                        <a href="{{ route('admin.registrations.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">Lihat Semua Aktivitas</a>
                    </div>
                </div>

                <!-- Donut Chart: Package Distribution -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5">
                    <h3 class="font-headline text-headline-sm text-on-surface mb-1">Distribusi Paket</h3>
                    <p class="font-body-sm text-body-sm text-outline mb-4">Status paket siswa aktif</p>
                    <div class="relative h-[200px] w-full flex justify-center">
                        <canvas id="packageChart"></canvas>
                    </div>
                    <!-- Custom Legend -->
                    <div class="mt-4 flex justify-center gap-4">
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

        <!-- Bottom spacing -->
        <div class="h-8"></div>
    </div>

    <!-- Chart Initializations -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const growthLabels = @json($growth->pluck('label'));
            const growthData = @json($growth->pluck('count'));
            const statusData = @json($statusData);
            const packageData = @json($packageData);

            // Common Chart Options
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#737785';
            Chart.defaults.scale.grid.color = 'rgba(194, 198, 214, 0.2)';

            // 1. Line Chart (Tren Pertumbuhan)
            const growthCtx = document.getElementById('growthChart').getContext('2d');
            const gradient = growthCtx.createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, 'rgba(11, 94, 215, 0.2)');
            gradient.addColorStop(1, 'rgba(11, 94, 215, 0)');

            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: growthLabels,
                    datasets: [{
                        label: 'Total Siswa',
                        data: growthData,
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
                        backgroundColor: ['#FFB300', '#2E7D32', '#ba1a1a'],
                        borderRadius: 6,
                        barThickness: 24
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
                        backgroundColor: ['#0B5ED7', '#FFB300', '#ba1a1a'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
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
