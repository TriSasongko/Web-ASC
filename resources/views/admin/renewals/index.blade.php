<x-sidebar-layout>
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Perpanjangan Paket</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Daftar siswa yang paketnya sudah mendekati/habis sesi dan menunggu konfirmasi kelanjutan.</p>
            </div>
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full font-label-md text-label-md bg-error-container text-on-error-container shrink-0">
                <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                {{ $enrollments->count() }} siswa
            </span>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            {{-- Mobile: kartu perpanjangan --}}
            <div class="md:hidden divide-y divide-outline-variant/30">
                @forelse ($enrollments as $enrollment)
                    @php
                        $student = $enrollment->student;
                        $class = $enrollment->schoolClass;
                        $program = $class?->program;
                        $total = $program?->total_sessions;
                        $completed = $enrollment->sessions_completed;
                        $left = $total === null ? null : max(0, $total - $completed);
                        $progress = $total ? min(100, (int) round($completed / $total * 100)) : 0;
                        $phone = preg_replace('/\D/', '', $student?->parent?->phone ?? '');
                    @endphp
                    <div class="p-4 hover:bg-surface-container-low/50 transition-colors" x-data="{ confirmOpen: false, declineOpen: false }">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-label-md text-label-md text-on-surface truncate">{{ $student?->full_name ?? '-' }}</p>
                                <p class="font-body-sm text-body-sm text-outline truncate mt-0.5">{{ $student?->parent?->name ?? '-' }}</p>
                                @if ($phone)
                                    <p class="font-body-sm text-body-sm text-outline truncate">{{ $student?->parent?->phone }}</p>
                                @endif
                            </div>
                            <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm {{ $left === 0 ? 'bg-error-container text-on-error-container' : 'bg-[#FFF8E1] text-[#B26A00]' }}">
                                {{ $total ? 'Sisa '.$left.'/'.$total : 'Per sesi' }}
                            </span>
                        </div>
                        <div class="mt-2">
                            <p class="font-body-sm text-body-sm text-on-surface">{{ $class?->name ?? '-' }}</p>
                            <p class="font-body-sm text-body-sm text-outline">{{ $program?->name ?? '-' }}</p>
                        </div>
                        @if ($total)
                            <div class="flex items-center justify-between gap-2 mt-2">
                                <span class="font-label-sm text-label-sm text-outline">{{ $progress }}%</span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-surface-container overflow-hidden mt-1">
                                <div class="h-full rounded-full bg-primary transition-all" style="width: {{ $progress }}%"></div>
                            </div>
                        @endif
                        <div class="mt-3">
                            @include('admin.renewals._actions', ['enrollment' => $enrollment])
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <span class="material-symbols-outlined text-outline text-[36px]">task_alt</span>
                        <p class="font-headline text-headline-sm text-on-surface mt-3">Tidak ada paket yang menunggu konfirmasi</p>
                        <p class="font-body-sm text-body-sm text-outline mt-1">Semua paket aktif dalam keadaan normal.</p>
                    </div>
                @endforelse
            </div>

            {{-- Desktop: tabel perpanjangan --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Orang Tua</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kelas / Program</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Sisa Sesi</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($enrollments as $enrollment)
                            @php
                                $student = $enrollment->student;
                                $class = $enrollment->schoolClass;
                                $program = $class?->program;
                                $total = $program?->total_sessions;
                                $completed = $enrollment->sessions_completed;
                                $left = $total === null ? null : max(0, $total - $completed);
                                $progress = $total ? min(100, (int) round($completed / $total * 100)) : 0;
                                $phone = preg_replace('/\D/', '', $student?->parent?->phone ?? '');
                                $wa = 'https://wa.me/'.preg_replace('/^0/', '62', $phone);
                                $waText = $left > 0
                                    ? 'Halo '.$student->parent->name.', paket '.$program->name.' an. '.$student->full_name.' tersisa '.$left.' pertemuan lagi. Apakah ingin memperpanjang paket?'
                                    : 'Halo '.$student->parent->name.', paket '.$program->name.' an. '.$student->full_name.' sudah habis ('.$completed.'/'.$total.'). Apakah ingin memperpanjang paket?';
                            @endphp

                            <tr x-data="{ confirmOpen: false, declineOpen: false }" class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-body-md text-body-md text-on-surface truncate">{{ $student?->full_name ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-body-sm text-body-sm text-on-surface">{{ $student?->parent?->name ?? '-' }}</p>
                                    @if ($phone)
                                        <p class="font-body-sm text-body-sm text-outline">{{ $student?->parent?->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-body-sm text-body-sm text-on-surface">{{ $class?->name ?? '-' }}</p>
                                    <p class="font-body-sm text-body-sm text-outline">{{ $program?->name ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm {{ $left === 0 ? 'bg-error-container text-on-error-container' : 'bg-[#FFF8E1] text-[#B26A00]' }}">
                                            {{ $total ? 'Sisa '.$left.'/'.$total : 'Per sesi' }}
                                        </span>
                                        @if ($total)
                                            <span class="font-label-sm text-label-sm text-outline">{{ $progress }}%</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @include('admin.renewals._actions', ['enrollment' => $enrollment])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <span class="material-symbols-outlined text-outline text-[36px]">task_alt</span>
                                    <p class="font-headline text-headline-sm text-on-surface mt-3">Tidak ada paket yang menunggu konfirmasi</p>
                                    <p class="font-body-sm text-body-sm text-outline mt-1">Semua paket aktif dalam keadaan normal.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-sidebar-layout>
