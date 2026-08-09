<x-sidebar-layout>
    <div class="space-y-6 max-w-3xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">E-Raport — {{ $student->full_name }}</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Rekap penilaian perkembangan periode {{ $development->period }}</p>
            </div>
            <a href="{{ route('eraport.pdf', [$student, $development->id]) }}"
                class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Unduh PDF
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 space-y-6">
            <div>
                <h3 class="font-headline text-headline-sm text-on-surface mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">person</span>
                    Identitas Siswa
                </h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Nama</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $student->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Coach</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $development->coach->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Program</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $development->schoolClass->program->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Periode</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $development->period }}</dd>
                    </div>
                </dl>
            </div>

            <div class="border-t border-outline-variant/30 pt-6">
                <h3 class="font-headline text-headline-sm text-on-surface mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#2E7D32]">event_available</span>
                    Kehadiran
                </h3>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                    {{ $attendanceCount }} pertemuan hadir
                </span>
            </div>

            <div class="border-t border-outline-variant/30 pt-6">
                <h3 class="font-headline text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">monitoring</span>
                    Penilaian Perkembangan
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <tbody>
                            <tr>
                                <td colspan="2" class="py-2 font-label-sm text-label-sm text-secondary uppercase tracking-wider">Penilaian Umum</td>
                            </tr>
                            @foreach (\App\Models\Development::umumAspects() as $key => $label)
                                <tr class="border-b border-outline-variant/30">
                                    <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface-variant">{{ $label }}</td>
                                    <td class="px-4 py-3 font-label-md text-label-md text-on-surface">{{ \App\Models\Development::scoreLabel($development->$key) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="py-2 pt-4 font-label-sm text-label-sm text-secondary uppercase tracking-wider">Penilaian Aspek Khusus</td>
                            </tr>
                            @foreach (\App\Models\Development::khususAspects() as $key => $label)
                                <tr class="border-b border-outline-variant/30">
                                    <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface-variant">{{ $label }}</td>
                                    <td class="px-4 py-3 font-label-md text-label-md text-on-surface">{{ \App\Models\Development::scoreLabel($development->$key) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($development->coach_note)
                <div class="border-t border-outline-variant/30 pt-6">
                    <h3 class="font-headline text-headline-sm text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#B26A00]">edit_note</span>
                        Catatan Coach
                    </h3>
                    <div class="bg-[#FFF8E1] border border-[#FFB300]/30 text-on-surface-variant rounded-lg px-4 py-3 font-body-sm text-body-sm">
                        {{ $development->coach_note }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-sidebar-layout>
