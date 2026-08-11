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
            <div class="overflow-x-auto">
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
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if ($phone)
                                            <a href="{{ $wa }}?text={{ urlencode('Halo '.$student->parent->name.', paket '.$program->name.' an. '.$student->full_name.' tersisa '.$left.' pertemuan lagi. Apakah ingin memperpanjang paket?') }}"
                                               target="_blank"
                                               class="inline-flex items-center justify-center gap-1.5 bg-[#E8F5E9] text-[#2E7D32] px-3 py-1.5 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">
                                                <span class="material-symbols-outlined text-[16px]">chat</span>
                                                Chat WA
                                            </a>
                                        @endif
                                        <button @click="confirmOpen = true" type="button"
                                            class="inline-flex items-center justify-center gap-1.5 bg-primary-container text-on-primary px-3 py-1.5 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">
                                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                            Konfirmasi
                                        </button>
                                        <button @click="declineOpen = true" type="button"
                                            class="inline-flex items-center justify-center gap-1.5 bg-error text-on-error px-3 py-1.5 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">
                                            <span class="material-symbols-outlined text-[16px]">block</span>
                                            Tidak Lanjut
                                        </button>

                                        <!-- Modal Konfirmasi Perpanjangan -->
                                        <div x-show="confirmOpen" x-cloak x-transition.opacity
                                            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
                                            @click="confirmOpen = false"></div>

                                        <div x-show="confirmOpen" x-cloak x-transition
                                            @keydown.escape.window="confirmOpen = false"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                            <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden">
                                                <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50">
                                                    <div>
                                                        <h3 class="font-headline text-headline-sm text-on-surface">Konfirmasi Perpanjangan</h3>
                                                        <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $student?->full_name }}</p>
                                                    </div>
                                                    <button @click="confirmOpen = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                                                        <span class="material-symbols-outlined text-[20px]">close</span>
                                                    </button>
                                                </div>

                                                <div class="px-6 py-5 space-y-2">
                                                    @if ($left > 0)
                                                        <p class="font-body-sm text-body-sm text-outline">Sisa <strong class="text-on-surface">{{ $left }}</strong> sesi dari periode saat ini akan <strong class="text-on-surface">dihabiskan dulu</strong>. Setelah sesi terakhir tercatat, periode paket baru ({{ $total }} sesi) akan dibuat otomatis.</p>
                                                    @else
                                                        <p class="font-body-sm text-body-sm text-outline">Periode saat ini ({{ $completed }}/{{ $total }} sesi) akan ditutup dan periode paket baru akan dibuat mulai dari sesi 0.</p>
                                                    @endif
                                                    <p class="font-body-sm text-body-sm text-outline">Riwayat sesi & periode lama tetap tersimpan.</p>
                                                    <p class="font-body-sm text-body-sm text-outline mb-2">Pastikan orang tua sudah setuju dan pembayaran sudah diterima.</p>

                                                    <form action="{{ route('admin.renewals.confirm', [$student, $enrollment]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-3 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                                                            Ya, Konfirmasi Perpanjangan
                                                        </button>
                                                    </form>
                                                </div>

                                                <div class="flex items-center justify-end px-6 py-3 border-t border-outline-variant/30">
                                                    <button @click="confirmOpen = false" type="button" class="inline-flex items-center justify-center gap-2 border border-outline-variant/50 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                                                        Batal
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Tidak Lanjut -->
                                        <div x-show="declineOpen" x-cloak x-transition.opacity
                                            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
                                            @click="declineOpen = false"></div>

                                        <div x-show="declineOpen" x-cloak x-transition
                                            @keydown.escape.window="declineOpen = false"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                            <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden">
                                                <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50">
                                                    <div>
                                                        <h3 class="font-headline text-headline-sm text-on-surface">Tidak Lanjut</h3>
                                                        <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $student?->full_name }}</p>
                                                    </div>
                                                    <button @click="declineOpen = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                                                        <span class="material-symbols-outlined text-[20px]">close</span>
                                                    </button>
                                                </div>

                                                <div class="px-6 py-5 space-y-3">
                                                    <p class="font-body-sm text-body-sm text-outline">Siswa akan <strong class="text-on-surface">dinonaktifkan dari kelas {{ $class?->name }}</strong> dan tidak melanjutkan ke periode paket berikutnya.</p>
                                                    <p class="font-body-sm text-body-sm text-outline">Riwayat paket & absensi tetap tersimpan. Aksi ini bersifat final.</p>

                                                    <form action="{{ route('admin.renewals.decline', [$student, $enrollment]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-error text-on-error px-3 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                                                            Ya, Tandai Tidak Lanjut
                                                        </button>
                                                    </form>
                                                </div>

                                                <div class="flex items-center justify-end px-6 py-3 border-t border-outline-variant/30">
                                                    <button @click="declineOpen = false" type="button" class="inline-flex items-center justify-center gap-2 border border-outline-variant/50 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                                                        Batal
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
