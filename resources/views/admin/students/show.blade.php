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

        @php
            $activeClassIds = $student->classes->filter(fn ($c) => (bool) $c->pivot->is_active)->pluck('id')->flip();
        @endphp

        @forelse ($student->classes as $class)
            @php
                $completed = $class->pivot->sessions_completed;
                $total = $class->program->total_sessions;
                $isPaket = $class->program->billing_type === 'per_paket';
                $left = $total === null ? null : max(0, $total - $completed);
                $isHistory = ! (bool) $class->pivot->is_active && $activeClassIds->has($class->id);
                $historyStatus = match ($class->pivot->renewal_status) {
                    'selesai' => 'Selesai',
                    'berhenti' => 'Berhenti',
                    'pindah' => 'Pindah',
                    'lanjut' => 'Lanjut',
                    default => 'Riwayat',
                };
                $status = null;
                if ($isPaket && $total) {
                    if ($completed >= $total) {
                        $status = ['label' => 'Paket Habis', 'color' => 'bg-error-container text-on-error-container'];
                    } elseif ($completed == $total - 1) {
                        $status = ['label' => 'Hampir Habis (sisa 1x)', 'color' => 'bg-[#FFF8E1] text-[#B26A00]'];
                    }
                }
            @endphp

            @if ($isHistory)
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6" x-data="{ open: false }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="material-symbols-outlined text-[#00687A]">history</span>
                            <div class="min-w-0">
                                <h3 class="font-headline text-headline-sm text-on-surface truncate">{{ $class->name }} — {{ $class->program->name }}</h3>
                                <p class="font-body-sm text-body-sm text-outline mt-0.5">
                                    Riwayat paket · {{ $completed }}{{ $total ? '/'.$total : '' }} pertemuan
                                    @if ($class->pivot->started_at)
                                        · {{ \Illuminate\Support\Carbon::parse($class->pivot->started_at)->format('d/m/Y') }}
                                        s.d. {{ $class->pivot->ended_at ? \Illuminate\Support\Carbon::parse($class->pivot->ended_at)->format('d/m/Y') : '-' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">{{ $historyStatus }}</span>
                            <button @click="open = !open" type="button" class="inline-flex items-center justify-center gap-1 border border-outline-variant/50 text-on-surface-variant px-3 py-1.5 rounded-lg font-label-sm text-label-sm hover:bg-surface-container transition-all">
                                <span x-text="open ? 'Tutup' : 'Lihat'">Lihat</span>
                                <span class="material-symbols-outlined text-[16px] transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                            </button>
                        </div>
                    </div>

                    <div x-show="open" x-cloak class="mt-5">
                        @php
                            $records = $attendanceLists[$class->pivot->id] ?? collect();
                        @endphp

                        @include('admin.students._attendance-grid', ['records' => $records, 'total' => $total])
                    </div>
                </div>
            @else
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

                @if (! $isPaket)
                    @php
                        $start = $class->pivot->started_at
                            ? \Illuminate\Support\Carbon::parse($class->pivot->started_at)
                            : ($class->pivot->created_at ? \Illuminate\Support\Carbon::parse($class->pivot->created_at) : null);
                    @endphp
                    <div class="mb-4 flex flex-wrap gap-x-6 gap-y-1 font-body-sm text-body-sm text-outline">
                        <span>Mulai periode: {{ $start?->format('d/m/Y') ?? '-' }}</span>
                        <span>Periode berikutnya (billing): {{ $start?->copy()->addMonth()?->format('d/m/Y') ?? '-' }}</span>
                    </div>
                @endif

                @php
                    $records = $attendanceLists[$class->pivot->id] ?? collect();
                @endphp

                @if ($isPaket && $total)
                    @include('admin.students._attendance-grid', ['records' => $records, 'total' => $total])
                @elseif ($records->isEmpty())
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
                        @elseif ($pivot->renewal_status === 'selesai')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Selesai — sudah lanjut ke periode baru</span>
                        @elseif ($pivot->renewal_status === 'pindah')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Pindah</span>
                        @elseif ($left <= 1)
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = true" type="button"
                                    class="inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded-lg font-label-sm text-label-sm text-on-primary hover:opacity-90 transition-all active:scale-95 {{ $left === 0 ? 'bg-error' : 'bg-[#B26A00]' }}">
                                    {{ $left === 0 ? 'Paket habis — Konfirmasi' : 'Sisa '.$left.'x — Konfirmasi' }}
                                </button>

                                <div x-show="open" x-cloak x-transition.opacity
                                    class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
                                    @click="open = false"></div>

                                <div x-show="open" x-cloak x-transition
                                    @keydown.escape.window="open = false"
                                    class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                    <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden">
                                        <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50">
                                            <div>
                                                <h3 class="font-headline text-headline-sm text-on-surface">{{ $left === 0 ? 'Paket Habis' : 'Sisa '.$left.' Pertemuan' }}</h3>
                                                <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $student->full_name }}</p>
                                            </div>
                                            <button @click="open = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                                                <span class="material-symbols-outlined text-[20px]">close</span>
                                            </button>
                                        </div>

                                        <div class="px-6 py-5 space-y-3">
                                            <p class="font-body-sm text-body-sm text-outline">Paket: {{ $class->program->name }} — {{ $fmt($class->program->price) }}</p>
                                            <p class="font-body-sm text-body-sm text-outline">Pertemuan: {{ $pivot->sessions_completed }}/{{ $total }} (sisa {{ $left }}x)</p>
                                            <p class="font-body-sm text-body-sm text-outline mb-2">Orang Tua: {{ $student->parent->name }} ({{ $student->parent->phone ?? '-' }})</p>

                                            @if ($phone)
                                                <a href="{{ $wa }}?text={{ urlencode('Halo '.$student->parent->name.', paket '.$class->program->name.' an. '.$student->full_name.' tersisa '.$left.' pertemuan lagi. Harga '.$fmt($class->program->price).'. Apakah ingin memperpanjang paket?') }}"
                                                   target="_blank" class="flex items-center justify-center gap-2 bg-[#E8F5E9] text-[#2E7D32] px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all">
                                                    <span class="material-symbols-outlined text-[16px]">chat</span>
                                                    Konfirmasi via WA
                                                </a>
                                            @else
                                                <p class="font-body-sm text-body-sm text-outline">No. HP orang tua belum diisi.</p>
                                            @endif

                                            <form action="{{ route('admin.class-students.renew', $pivot->id) }}" method="POST" class="space-y-2">
                                                @csrf @method('PATCH')
                                                <x-text-input type="text" name="renewal_note" placeholder="Catatan (opsional)" />
                                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">Perpanjang Paket</button>
                                            </form>

                                            <form action="{{ route('admin.class-students.stop', $pivot->id) }}" method="POST" class="space-y-2"
                                                  onsubmit="return confirm('Tandai '.$student->full_name.' sebagai BERHENTI?')">
                                                @csrf @method('PATCH')
                                                <x-text-input type="text" name="renewal_note" placeholder="Alasan berhenti (opsional)" />
                                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-error text-on-error px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">Tandai Berhenti</button>
                                            </form>

                                            <div class="flex items-center justify-end pt-1">
                                                <button @click="open = false" type="button" class="inline-flex items-center justify-center gap-2 border border-outline-variant/50 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            @endif
        @empty
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-10 text-center">
                <span class="material-symbols-outlined text-outline text-[32px]">school</span>
                <p class="font-body-sm text-body-sm text-outline mt-2">Siswa ini belum ditempatkan di kelas manapun.</p>
            </div>
        @endforelse
    </div>
</x-sidebar-layout>
