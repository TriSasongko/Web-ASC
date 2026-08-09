<x-sidebar-layout>
    @php
        $initials = collect(explode(' ', $student->full_name))
            ->filter()
            ->map(fn ($word) => mb_substr($word, 0, 1))
            ->take(2)
            ->join('');
        $scoreBadge = function (?string $value): string {
            return match ($value) {
                'sangat_baik' => 'bg-primary-container text-on-primary',
                'baik' => 'bg-secondary-container text-on-secondary-container',
                'cukup' => 'bg-surface-container-high text-on-surface-variant',
                'kurang' => 'bg-error-container text-on-error-container',
                default => 'bg-surface-container-low text-outline',
            };
        };
    @endphp

    <div class="space-y-6">
        <!-- Page Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-on-surface-variant mb-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors font-label-sm text-label-sm">Dashboard</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="text-on-surface font-label-sm text-label-sm">{{ $student->full_name }}</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="font-label-sm text-label-sm">{{ $development->period }}</span>
                </div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface flex items-center gap-3">
                    E-Raport
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary-fixed/50 text-primary font-label-sm text-label-sm">
                        {{ $development->period }}
                    </span>
                </h2>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="navigator.share ? navigator.share({title: 'E-Raport {{ $student->full_name }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href)"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary-fixed/50 transition-colors shadow-sm bg-surface-container-lowest">
                    <span class="material-symbols-outlined text-[18px]">share</span>
                    Bagikan
                </button>
                <a href="{{ route('eraport.pdf', [$student, $development->id]) }}"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:bg-primary/90 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Unduh PDF
                </a>
            </div>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
            <!-- Left Column (Profile & Summary) -->
            <div class="lg:col-span-4 flex flex-col gap-gutter">
                <!-- Student Profile Card -->
                <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,71,169,0.05)] p-6 relative overflow-hidden group hover:-translate-y-0.5 transition-transform duration-300">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-secondary-container to-primary"></div>

                    <div class="flex items-start gap-5">
                        <div class="relative">
                            <div class="w-20 h-20 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-headline text-headline-md font-bold border-2 border-surface-container-lowest shadow-sm">
                                {{ $initials }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-secondary-container rounded-full flex items-center justify-center border-2 border-surface-container-lowest">
                                <span class="material-symbols-outlined text-on-secondary-container" style="font-size: 14px;">verified</span>
                            </div>
                        </div>

                        <div class="flex-1 pt-1">
                            <h3 class="font-headline text-headline-sm text-on-surface mb-1">{{ $student->full_name }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mb-3">ID: {{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container-low border border-outline-variant/30">
                                <span class="w-2 h-2 rounded-full bg-secondary-fixed-dim"></span>
                                <span class="font-label-sm text-label-sm text-on-surface">
                                    {{ $development->schoolClass->name }}
                                    @if ($development->schoolClass->level_label)
                                        · {{ $development->schoolClass->level_label }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-5 border-t border-outline-variant/30 grid grid-cols-2 gap-4">
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider text-[10px]">Coach</p>
                            <p class="font-body-sm text-body-sm text-on-surface font-medium flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary/70" style="font-size: 16px;">person</span>
                                {{ $development->coach->name }}
                            </p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider text-[10px]">Program</p>
                            <p class="font-body-sm text-body-sm text-on-surface font-medium flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary/70" style="font-size: 16px;">pool</span>
                                {{ $development->schoolClass->program->name }}
                            </p>
                        </div>
                        @if ($scheduleLabel)
                            <div class="col-span-2">
                                <p class="font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider text-[10px]">Jadwal Latihan</p>
                                <p class="font-body-sm text-body-sm text-on-surface font-medium flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary/70" style="font-size: 16px;">schedule</span>
                                    {{ $scheduleLabel }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progress Trend Chart -->
                <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,71,169,0.05)] p-5 flex flex-col hover:-translate-y-0.5 transition-transform duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-label-md text-label-md text-on-surface-variant">Tren Perkembangan</h3>
                        <span class="material-symbols-outlined text-on-surface-variant/70 text-[18px]">show_chart</span>
                    </div>
                    @if (count($trendData['values']) > 1)
                        <div class="h-32 w-full relative">
                            <canvas id="progressTrendChart"
                                data-labels='{{ json_encode($trendData['labels']) }}'
                                data-values='{{ json_encode($trendData['values']) }}'></canvas>
                        </div>
                    @else
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Data tren tersedia setelah lebih dari satu periode penilaian.</p>
                    @endif
                </div>

                <!-- Summary Metrics (Stacked) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-gutter">
                    <!-- Kehadiran -->
                    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,71,169,0.05)] p-5 flex items-center justify-between hover:-translate-y-0.5 transition-transform duration-300">
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant mb-1">Tingkat Kehadiran</p>
                            <p class="font-headline text-headline-md text-on-surface">
                                {{ $attendancePercent !== null ? $attendancePercent.'%' : $attendanceCount.' pertemuan' }}
                            </p>
                            <p class="font-body-sm text-body-sm text-secondary flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined" style="font-size: 14px;">event_available</span>
                                {{ $attendanceCount }} dari {{ $totalSessions ?? '-' }} sesi hadir
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary" style="font-size: 24px;">event_available</span>
                        </div>
                    </div>

                    <!-- Penilaian Keseluruhan -->
                    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,71,169,0.05)] p-5 flex items-center justify-between hover:-translate-y-0.5 transition-transform duration-300">
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant mb-1">Penilaian Keseluruhan</p>
                            <p class="font-headline text-headline-md text-primary">{{ $overallScore['label'] }}</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                                {{ $overallScore['key'] === 'sangat_baik' ? 'Perkembangan sangat memuaskan' : ($overallScore['key'] === 'baik' ? 'Perkembangan baik' : ($overallScore['key'] === 'cukup' ? 'Perkembangan cukup, perlu ditingkatkan' : 'Perlu perhatian lebih')) }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-primary" style="font-size: 24px;">military_tech</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column (Matrix & Narrative) -->
            <div class="lg:col-span-8 flex flex-col gap-gutter">
                <!-- Skill Matrix -->
                <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,71,169,0.05)] p-6 flex flex-col hover:-translate-y-0.5 transition-transform duration-300">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-outline-variant/30">
                        <h3 class="font-headline text-headline-sm text-on-surface">Penilaian Perkembangan</h3>
                        <span class="font-label-md text-label-md px-3 py-1 bg-surface-container-low rounded-full text-on-surface-variant">{{ $development->schoolClass->name }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Doughnut Chart -->
                        @php
                            $chartPalette = ['#0047a9', '#4cd7f6', '#57dffe', '#314d7c', '#b0c6ff', '#d9e2ff'];
                            $chartColors = array_map(
                                fn ($i) => $chartPalette[$i % count($chartPalette)],
                                array_keys($radarData['labels'])
                            );
                        @endphp
                        <div class="col-span-1 border-r-0 md:border-r border-outline-variant/30 pr-0 md:pr-4">
                            <h4 class="font-label-md text-label-md text-on-surface-variant text-center mb-2">Dimensi Penilaian</h4>
                            <div class="relative w-40 h-40 mx-auto flex items-center justify-center">
                                <canvas id="skillRadarChart"
                                    data-labels='{{ json_encode($radarData['labels']) }}'
                                    data-values='{{ json_encode($radarData['values']) }}'
                                    data-colors='{{ json_encode($chartColors) }}'></canvas>
                            </div>
                            <ul class="mt-5 space-y-2">
                                @foreach ($radarData['labels'] as $i => $label)
                                    <li class="flex items-center justify-between gap-2">
                                        <span class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface-variant min-w-0">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $chartColors[$i] }}"></span>
                                            <span class="truncate">{{ $label }}</span>
                                        </span>
                                        <span class="font-label-sm text-label-sm text-on-surface shrink-0">
                                            {{ \App\Models\Development::scoreLabel($radarData['keys'][$i] ?? null) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Skill Matrix -->
                        <div class="col-span-1 md:col-span-2 overflow-x-auto custom-scrollbar pb-2">
                            <table class="w-full text-left border-collapse min-w-[560px]">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider border-b border-outline-variant/50 w-3/5">Aspek Penilaian</th>
                                    <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider border-b border-outline-variant/50">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="py-2 pt-4 px-4 font-label-md text-label-md text-secondary uppercase tracking-wider">Penilaian Umum</td>
                                </tr>
                                @foreach (\App\Models\Development::umumAspects() as $key => $label)
                                    <tr class="group hover:bg-secondary-fixed/10 transition-colors">
                                        <td class="py-4 px-4 border-b border-outline-variant/20">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded bg-primary-fixed/50 flex items-center justify-center text-primary shrink-0">
                                                    <span class="material-symbols-outlined" style="font-size: 18px;">monitoring</span>
                                                </div>
                                                <span class="font-body-md text-body-md text-on-surface font-medium">{{ $label }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 border-b border-outline-variant/20">
                                            <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full {{ $scoreBadge($development->$key) }} font-label-md text-label-md">
                                                {{ \App\Models\Development::scoreLabel($development->$key) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <!-- Coach's Narrative -->
                <div class="bg-primary/5 rounded-xl border border-primary/10 p-6 relative overflow-hidden">
                    <span class="material-symbols-outlined absolute -top-4 -right-4 text-[120px] text-primary/5 rotate-12 select-none pointer-events-none">format_quote</span>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">
                            {{ strtoupper(mb_substr($development->coach->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-label-md text-label-md text-on-surface">Catatan Coach</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $development->coach->name }}</p>
                        </div>
                    </div>

                    @if ($development->coach_note)
                        <p class="font-body-md text-body-md text-on-surface/80 italic leading-relaxed relative z-10">
                            "{{ $development->coach_note }}"
                        </p>
                    @else
                        <p class="font-body-md text-body-md text-on-surface/60 italic relative z-10">
                            Belum ada catatan dari coach untuk periode ini.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/eraport.js'])
</x-sidebar-layout>
