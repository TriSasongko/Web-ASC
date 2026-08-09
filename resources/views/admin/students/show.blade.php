<x-sidebar-layout>
    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Rekap — {{ $student->full_name }}</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Rincian paket dan perkembangan siswa.</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-[#00687A]">family_restroom</span>
                <h3 class="font-headline text-headline-sm text-on-surface">Data Orang Tua</h3>
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">Nama</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $student->parent->name }}</dd>
                </div>
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">No. HP</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">
                        @if ($student->parent->phone)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $student->parent->phone)) }}"
                               target="_blank" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                {{ $student->parent->phone }} (Chat WA)
                            </a>
                        @else
                            -
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        @forelse ($student->classes as $class)
            @php
                $completed = $class->pivot->sessions_completed;
                $total = $class->program->total_sessions;
                $isPaket = $class->program->billing_type === 'per_paket';
                $left = $total === null ? null : max(0, $total - $completed);
                $status = null;
                if ($isPaket && $total) {
                    if ($completed >= $total) {
                        $status = ['label' => 'Paket Habis', 'color' => 'bg-error-container text-on-error-container'];
                    } elseif ($completed == $total - 1) {
                        $status = ['label' => 'Hampir Habis (sisa 1x)', 'color' => 'bg-[#FFF8E1] text-[#B26A00]'];
                    }
                }
            @endphp

            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-5">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">
                            {{ $class->name }} — {{ $class->program->name }}
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-primary-container text-on-primary align-middle ml-1">{{ $class->level_label ?? '-' }}</span>
                        </h3>
                    </div>
                    <div class="text-right">
                        <p class="font-label-md text-label-md text-on-surface">
                            @if ($isPaket)
                                {{ $completed }}/{{ $total ?? '∞' }} pertemuan
                            @else
                                Kompetitif (bulanan)
                            @endif
                        </p>
                        @if ($status)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm mt-1 {{ $status['color'] }}">{{ $status['label'] }}</span>
                        @endif
                    </div>
                </div>

                @php
                    $records = $attendanceLists[$class->id] ?? collect();
                @endphp

                @if ($records->isEmpty())
                    <p class="font-body-sm text-body-sm text-outline">Belum ada catatan absensi untuk kelas ini.</p>
                @else
                    <div class="overflow-x-auto rounded-lg border border-outline-variant/30">
                        <table class="w-full text-left">
                            <thead class="bg-surface-container-low">
                                <tr>
                                    <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">No</th>
                                    <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lokasi</th>
                                    <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Diabsen Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/30">
                                @foreach ($records as $i => $r)
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="px-4 py-2.5 font-body-sm text-body-sm text-outline">{{ $i + 1 }}</td>
                                        <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface">{{ $r->attendance_date->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface">{{ $r->location ?? '-' }}</td>
                                        <td class="px-4 py-2.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Hadir</span>
                                        </td>
                                        <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface">{{ $r->recorder?->name ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($isPaket && $total && $left !== null)
                    @php
                        $pivot = $class->pivot;
                        $phone = preg_replace('/\D/', '', $student->parent->phone ?? '');
                        $wa = 'https://wa.me/'.preg_replace('/^0/', '62', $phone);
                    @endphp
                    <div class="mt-5 pt-5 border-t border-outline-variant/30">
                        @if ($pivot->renewal_status === 'berhenti')
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Berhenti</span>
                                <form action="{{ route('admin.class-students.activate', $pivot->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-3 py-1.5 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">Aktifkan Kembali</button>
                                </form>
                            </div>
                        @elseif ($pivot->renewal_status === 'lanjut')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E6F8FC] text-secondary">Lanjut</span>
                        @elseif ($left <= 1)
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = ! open" type="button"
                                    class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded-lg font-label-sm text-label-sm text-on-primary hover:opacity-90 transition-all active:scale-95 {{ $left === 0 ? 'bg-error' : 'bg-[#B26A00]' }}">
                                    {{ $left === 0 ? 'Paket habis — Konfirmasi' : 'Sisa '.$left.'x — Konfirmasi' }}
                                </button>

                                <div x-show="open" @click.outside="open = false" x-transition
                                    class="absolute left-0 z-20 mt-2 w-80 bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-[0px_16px_48px_rgba(23,32,51,0.16)] p-5">
                                    <p class="font-label-md text-label-md text-on-surface">{{ $student->full_name }}</p>
                                    <p class="font-body-sm text-body-sm text-outline mt-1">Paket: {{ $class->program->name }} — {{ $fmt($class->program->price) }}</p>
                                    <p class="font-body-sm text-body-sm text-outline mt-1">Pertemuan: {{ $pivot->sessions_completed }}/{{ $total }} (sisa {{ $left }}x)</p>
                                    <p class="font-body-sm text-body-sm text-outline mt-1 mb-3">Orang Tua: {{ $student->parent->name }} ({{ $student->parent->phone ?? '-' }})</p>

                                    @if ($phone)
                                        <a href="{{ $wa }}?text={{ urlencode('Halo '.$student->parent->name.', paket '.$class->program->name.' an. '.$student->full_name.' tersisa '.$left.' pertemuan lagi. Harga '.$fmt($class->program->price).'. Apakah ingin memperpanjang paket?') }}"
                                           target="_blank" class="flex items-center justify-center gap-2 bg-[#E8F5E9] text-[#2E7D32] px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all mb-3">
                                            <span class="material-symbols-outlined text-[16px]">chat</span>
                                            Konfirmasi via WA
                                        </a>
                                    @else
                                        <p class="font-body-sm text-body-sm text-outline mb-3">No. HP orang tua belum diisi.</p>
                                    @endif

                                    <form action="{{ route('admin.class-students.renew', $pivot->id) }}" method="POST" class="space-y-2">
                                        @csrf @method('PATCH')
                                        <x-text-input type="text" name="renewal_note" placeholder="Catatan (opsional)" />
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">Perpanjang Paket</button>
                                    </form>

                                    <form action="{{ route('admin.class-students.stop', $pivot->id) }}" method="POST" class="mt-3 space-y-2"
                                          onsubmit="return confirm('Tandai '.$student->full_name.' sebagai BERHENTI?')">
                                        @csrf @method('PATCH')
                                        <x-text-input type="text" name="renewal_note" placeholder="Alasan berhenti (opsional)" />
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-error text-on-error px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">Tandai Berhenti</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-10 text-center">
                <span class="material-symbols-outlined text-outline text-[32px]">school</span>
                <p class="font-body-sm text-body-sm text-outline mt-2">Siswa ini belum ditempatkan di kelas manapun.</p>
            </div>
        @endforelse
    </div>
</x-sidebar-layout>
