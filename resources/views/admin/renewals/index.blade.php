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

            <div x-data="{ confirmOpen: false, declineOpen: false }" class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex items-center gap-4 min-w-0 flex-1">
                        <div class="w-12 h-12 rounded-full bg-secondary-container text-secondary flex items-center justify-center font-label-lg text-label-lg shrink-0">
                            {{ strtoupper(substr($student?->full_name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-headline text-headline-sm text-on-surface truncate">{{ $student?->full_name ?? '-' }}</p>
                            <p class="font-body-sm text-body-sm text-outline truncate">
                                Orang Tua: {{ $student?->parent?->name ?? '-' }}
                                @if ($phone)
                                    · <a href="{{ $wa }}?text={{ urlencode('Halo '.$student->parent->name.', paket '.$program->name.' an. '.$student->full_name.' tersisa '.$left.' pertemuan lagi. Apakah ingin memperpanjang paket?') }}"
                                       target="_blank" class="text-primary font-label-md text-label-md hover:underline">Chat WA</a>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 shrink-0">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-primary-container text-on-primary">{{ $class?->name ?? '-' }}</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container-low text-on-surface-variant">{{ $program?->name ?? '-' }}</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm {{ $left === 0 ? 'bg-error-container text-on-error-container' : 'bg-[#FFF8E1] text-[#B26A00]' }}">
                            {{ $total ? 'Sisa '.$left.' dari '.$total.' sesi' : 'Per sesi' }}
                        </span>
                    </div>
                </div>

                @if ($total)
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-label-sm text-label-sm text-outline">Pertemuan: {{ $completed }}/{{ $total }}</span>
                            <span class="font-label-sm text-label-sm text-on-surface">{{ $progress }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-surface-container-low overflow-hidden">
                            <div class="h-full rounded-full {{ $left === 0 ? 'bg-error' : 'bg-[#B26A00]' }}" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="mt-5 flex flex-wrap gap-3">
                    <button @click="confirmOpen = true" type="button"
                        class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Konfirmasi Perpanjangan
                    </button>
                    <button @click="declineOpen = true" type="button"
                        class="inline-flex items-center justify-center gap-2 bg-error text-on-error px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">block</span>
                        Tidak Lanjut
                    </button>
                </div>

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
                            <p class="font-body-sm text-body-sm text-outline">Periode saat ini ({{ $completed }}/{{ $total }} sesi) akan ditutup dan periode paket baru akan dibuat mulai dari sesi 0.</p>
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
        @empty
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-12 text-center">
                <span class="material-symbols-outlined text-outline text-[36px]">task_alt</span>
                <p class="font-headline text-headline-sm text-on-surface mt-3">Tidak ada paket yang menunggu konfirmasi</p>
                <p class="font-body-sm text-body-sm text-outline mt-1">Semua paket aktif dalam keadaan normal.</p>
            </div>
        @endforelse
    </div>
</x-sidebar-layout>
