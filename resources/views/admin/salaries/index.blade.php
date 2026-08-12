@php
    $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    $dateFmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->translatedFormat('d M Y') : '-';
    $totalSesi = $coaches->sum(fn ($c) => $c->sessions->count());
    $totalBelumDibayar = $coaches->sum(fn ($c) => $c->total);
    $coachData = $coaches->map(fn ($c) => [
        'id' => $c->id,
        'name' => $c->name,
        'session_limit' => $c->session_limit,
        'session_count' => $c->sessions->count(),
        'total_display' => $fmt($c->total),
        'sessions' => $c->sessions->map(fn ($s) => [
            'key' => $s['first_attendance_id'],
            'date' => $dateFmt($s['attendance_date']),
            'class' => ($s['class_name'] ?? '-').($s['program_name'] ? ' · '.$s['program_name'] : ''),
            'label' => $s['paralel']
                ? 'Paralel ('.$s['session1_count'].' + '.$s['session2_count'].')'
                : 'Sesi '.($s['session2_count'] > 0 ? 2 : 1),
            'child_count' => $s['child_count'],
            'nominal' => $fmt($s['nominal']),
        ])->all(),
        'payments' => $c->payments->map(fn ($p) => [
            'id' => $p->id,
            'date' => $dateFmt($p->paid_at),
            'session_count' => $p->session_count,
            'note' => $p->note ?? '-',
            'amount' => $fmt($p->amount),
        ])->all(),
    ])->values()->all();
@endphp

<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Honor Pelatih</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Rekap honor dihitung otomatis dari data absensi program non-kompetitif. Klik nama pelatih untuk melihat rincian sesi dan riwayat pembayaran.</p>
            </div>
        </div>

        {{-- Stat overview --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-primary-container/60 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-[22px]">group</span>
                </div>
                <div class="min-w-0">
                    <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Pelatih Aktif</p>
                    <p class="font-headline text-headline-sm text-on-surface mt-0.5">{{ $coaches->count() }}</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-5 flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-primary-container/60 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-[22px]">event_available</span>
                </div>
                <div class="min-w-0">
                    <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Sesi Belum Dibayar</p>
                    <p class="font-headline text-headline-sm text-on-surface mt-0.5">{{ $totalSesi }}</p>
                </div>
            </div>
            <div class="bg-primary rounded-xl p-5 flex items-center gap-3 shadow-[0px_4px_20px_rgba(23,32,51,0.06)]">
                <div class="w-11 h-11 rounded-full bg-on-primary/15 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-primary text-[22px]">payments</span>
                </div>
                <div class="min-w-0">
                    <p class="font-label-sm text-label-sm text-on-primary/80 uppercase tracking-wider">Total Belum Dibayar</p>
                    <p class="font-headline text-headline-sm text-on-primary mt-0.5">{{ $fmt($totalBelumDibayar) }}</p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-2 bg-error-container text-on-error-container border border-error/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">error</span>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-primary text-[20px]">payments</span>
                <h3 class="font-headline text-headline-sm text-on-surface">Nominal Honor per Sesi</h3>
            </div>
            <form action="{{ route('admin.salaries.rates') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @csrf @method('PUT')
                <div>
                    <x-input-label for="rate_reguler_satu" value="Reguler/Private 1 anak" />
                    <x-text-input id="rate_reguler_satu" name="rate_reguler_satu" type="number" class="mt-1 block w-full"
                                  value="{{ old('rate_reguler_satu', $settings->rate_reguler_satu) }}" required />
                </div>
                <div>
                    <x-input-label for="rate_reguler_dua_plus" value="Reguler/Private 2+ anak" />
                    <x-text-input id="rate_reguler_dua_plus" name="rate_reguler_dua_plus" type="number" class="mt-1 block w-full"
                                  value="{{ old('rate_reguler_dua_plus', $settings->rate_reguler_dua_plus) }}" required />
                </div>
                <div>
                    <x-input-label for="rate_paralel_dua" value="Paralel total 2 anak" />
                    <x-text-input id="rate_paralel_dua" name="rate_paralel_dua" type="number" class="mt-1 block w-full"
                                  value="{{ old('rate_paralel_dua', $settings->rate_paralel_dua) }}" required />
                </div>
                <div>
                    <x-input-label for="rate_paralel_banyak" value="Paralel total 3+ anak" />
                    <x-text-input id="rate_paralel_banyak" name="rate_paralel_banyak" type="number" class="mt-1 block w-full"
                                  value="{{ old('rate_paralel_banyak', $settings->rate_paralel_banyak) }}" required />
                </div>
                <div class="md:col-span-2 lg:col-span-4 flex justify-end pt-2">
                    <x-primary-button>Simpan Nominal</x-primary-button>
                </div>
            </form>
            @if ($errors->any())
                <div class="mt-4">
                    <x-input-error :messages="$errors->all()" class="mt-2" />
                </div>
            @endif
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden"
             x-data="{
                 coaches: @js($coachData),
                 selected: null,
                 csrfToken: @js(csrf_token()),
                 paymentsBase: @js(url('/admin/salary-payments')),
                 openDetail(id) {
                     this.selected = this.coaches.find(c => c.id === id);
                     $dispatch('open-modal', 'coach-detail');
                 },
             }">
            <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-[22px]">summarize</span>
                    <div>
                        <p class="font-headline text-headline-sm text-on-surface">Rekap Honor</p>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">
                            {{ $coaches->count() }} pelatih · {{ $totalSesi }} sesi belum dibayar
                            · <span class="font-label-md text-label-md text-on-surface">{{ $fmt($totalBelumDibayar) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            @if ($coaches->isEmpty())
                <div class="p-10 text-center font-body-sm text-body-sm text-outline border-t border-outline-variant/30">
                    Belum ada pelatih terdaftar.
                </div>
            @endif

            @if ($coaches->isNotEmpty())
                <div class="overflow-x-auto border-t border-outline-variant/30">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th class="px-5 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Pelatih</th>
                                <th class="px-5 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Sesi Belum Dibayar</th>
                                <th class="px-5 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Total</th>
                                <th class="px-5 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Batas Honor</th>
                                <th class="px-5 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            @foreach ($coaches as $coach)
                                @php
                                    $sessionCount = $coach->sessions->count();
                                    $progress = $coach->session_limit > 0 ? min(100, ($sessionCount / $coach->session_limit) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-surface-container-low/50 transition-colors">
                                    <td class="px-5 py-3.5">
                                        <button type="button" @click="openDetail({{ $coach->id }})"
                                                class="flex items-center gap-2 group text-left min-w-0">
                                            <span class="material-symbols-outlined text-primary text-[20px] shrink-0">group</span>
                                            <span class="min-w-0">
                                                <span class="block font-label-md text-label-md text-primary truncate group-hover:underline">{{ $coach->name }}</span>
                                                <span class="block font-body-sm text-body-sm text-outline">Lihat rincian & riwayat</span>
                                            </span>
                                        </button>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-label-md text-label-md text-on-surface whitespace-nowrap">{{ $sessionCount }}/{{ $coach->session_limit }} sesi</span>
                                            @if ($sessionCount === 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Lunas</span>
                                            @endif
                                        </div>
                                        <div class="h-1.5 w-36 max-w-full rounded-full bg-surface-container overflow-hidden mt-1.5">
                                            <div class="h-full rounded-full bg-primary transition-all" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 font-label-md text-label-md text-on-surface whitespace-nowrap">{{ $fmt($coach->total) }}</td>
                                    <td class="px-5 py-3.5">
                                        <form action="{{ route('admin.salaries.limit', $coach->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf @method('PUT')
                                            <select id="session_limit_{{ $coach->id }}" name="session_limit"
                                                    class="bg-surface-container-low border border-outline-variant/50 rounded-lg px-2 py-1.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                                @foreach ($limitOptions as $option)
                                                    <option value="{{ $option }}" @selected($coach->session_limit === $option)>{{ $option }} sesi</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="inline-flex items-center justify-center border border-primary text-primary px-2.5 py-1.5 rounded-lg font-label-sm text-label-sm hover:bg-primary-container hover:text-on-primary transition-all">
                                                Set
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <form action="{{ route('admin.salaries.pay', $coach->id) }}" method="POST"
                                              onsubmit="return confirm('Tandai honor {{ addslashes($coach->name) }} sebesar {{ $fmt($coach->total) }} ({{ $sessionCount }} sesi) sebagai dibayar?')">
                                            @csrf
                                            <button type="submit" @disabled($sessionCount === 0)
                                                    class="inline-flex items-center justify-center gap-2 bg-primary text-on-primary px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all shadow-sm disabled:opacity-40 disabled:cursor-not-allowed whitespace-nowrap">
                                                <span class="material-symbols-outlined text-[16px]">payments</span>
                                                Tandai Dibayar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <x-modal name="coach-detail" maxWidth="2xl">
        <template x-if="selected">
            <div>
                <div class="px-6 pt-6 pb-4 flex items-start justify-between gap-4 border-b border-outline-variant/30">
                    <div class="min-w-0">
                        <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Detail Honor</p>
                        <h3 class="font-headline text-headline-sm text-on-surface mt-0.5 truncate" x-text="selected.name"></h3>
                        <p class="font-body-sm text-body-sm text-outline mt-1">
                            <span x-text="`${selected.session_count}/${selected.session_limit} sesi belum dibayar`"></span>
                            · <span x-text="selected.total_display"></span>
                        </p>
                    </div>
                    <button type="button" @click="$dispatch('close-modal', 'coach-detail')"
                            class="p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container-low transition-colors shrink-0" title="Tutup">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                    <h4 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-2">Sesi Belum Dibayar</h4>
                    <div class="overflow-x-auto rounded-lg border border-outline-variant/30">
                        <template x-if="selected.sessions.length">
                            <table class="w-full text-left">
                                <thead class="bg-surface-container-low">
                                    <tr>
                                        <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kelas</th>
                                        <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Sesi</th>
                                        <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Jumlah Anak</th>
                                        <th class="px-4 py-2.5 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/30">
                                    <template x-for="s in selected.sessions" :key="s.key">
                                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface whitespace-nowrap" x-text="s.date"></td>
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface" x-text="s.class"></td>
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface" x-text="s.label"></td>
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface" x-text="`${s.child_count} anak`"></td>
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface text-right whitespace-nowrap" x-text="s.nominal"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                        <template x-if="!selected.sessions.length">
                            <p class="px-4 py-4 font-body-sm text-body-sm text-outline">Belum ada sesi yang belum dibayar.</p>
                        </template>
                    </div>

                    <h4 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mt-6 mb-2">Riwayat Pembayaran</h4>
                    <div class="overflow-x-auto rounded-lg border border-outline-variant/30">
                        <template x-if="selected.payments.length">
                            <table class="w-full text-left">
                                <thead class="bg-surface-container-low">
                                    <tr>
                                        <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Jumlah Sesi</th>
                                        <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Catatan</th>
                                        <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-right">Nominal</th>
                                        <th class="px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/30">
                                    <template x-for="payment in selected.payments" :key="payment.id">
                                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface whitespace-nowrap" x-text="payment.date"></td>
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface" x-text="`${payment.session_count} sesi`"></td>
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface" x-text="payment.note"></td>
                                            <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface text-right whitespace-nowrap" x-text="payment.amount"></td>
                                            <td class="px-4 py-2.5 text-right">
                                                <form :action="`${paymentsBase}/${payment.id}`" method="POST"
                                                      @submit.prevent="if (confirm('Hapus catatan pembayaran ini? Batch honor akan dibuka kembali.')) $el.submit()">
                                                    <input type="hidden" name="_token" :value="csrfToken">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                        <template x-if="!selected.payments.length">
                            <p class="px-4 py-4 font-body-sm text-body-sm text-outline">Belum ada riwayat pembayaran.</p>
                        </template>
                    </div>
                </div>
            </div>
            </template>
        </x-modal>
        </div>
    </div>
</x-sidebar-layout>
