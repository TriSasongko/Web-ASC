@php
    $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    $dateFmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->translatedFormat('d M Y') : '-';
@endphp

<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Honor Pelatih</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Rekap honor dihitung otomatis dari data absensi program non-kompetitif. Hanya terlihat oleh admin.</p>
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

        <div class="space-y-4">
            @forelse ($coaches as $coach)
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                    <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-headline text-headline-sm text-on-surface">{{ $coach->name }}</h3>
                            <p class="font-body-sm text-body-sm text-outline mt-0.5">
                                {{ $coach->sessions->count() }}/{{ $coach->session_limit }} sesi belum dibayar
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <form action="{{ route('admin.salaries.limit', $coach->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <x-input-label for="session_limit_{{ $coach->id }}" value="Batas honor" class="!mb-0" />
                                <select id="session_limit_{{ $coach->id }}" name="session_limit"
                                        class="bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                    @foreach ($limitOptions as $option)
                                        <option value="{{ $option }}" @selected($coach->session_limit === $option)>{{ $option }} sesi</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="inline-flex items-center justify-center border border-primary text-primary px-3 py-2 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                                    Set
                                </button>
                            </form>
                            <form action="{{ route('admin.salaries.pay', $coach->id) }}" method="POST"
                                  onsubmit="return confirm('Tandai honor {{ addslashes($coach->name) }} sebesar {{ $fmt($coach->total) }} ({{ $coach->sessions->count() }} sesi) sebagai dibayar?')">
                                @csrf
                                <button type="submit" @disabled($coach->sessions->isEmpty())
                                        class="inline-flex items-center justify-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                                    <span class="material-symbols-outlined text-[18px]">payments</span>
                                    Tandai Dibayar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="px-5 py-2">
                        <div class="h-2 rounded-full bg-surface-container overflow-hidden">
                            <div class="h-full rounded-full bg-primary transition-all"
                                 style="width: {{ min(100, $coach->session_limit > 0 ? ($coach->sessions->count() / $coach->session_limit) * 100 : 0) }}%"></div>
                        </div>
                    </div>

                    <div class="p-5">
                        @if ($coach->sessions->isNotEmpty())
                            <div class="overflow-x-auto rounded-lg border border-outline-variant/30">
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
                                        @foreach ($coach->sessions as $session)
                                            @php
                                                $sessionLabel = $session['paralel']
                                                    ? 'Paralel ('.$session['session1_count'].' + '.$session['session2_count'].')'
                                                    : 'Sesi '.($session['session2_count'] > 0 ? 2 : 1);
                                            @endphp
                                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                                <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface whitespace-nowrap">{{ $dateFmt($session['attendance_date']) }}</td>
                                                <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface">{{ $session['class_name'] }}<span class="text-outline"> · {{ $session['program_name'] }}</span></td>
                                                <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface">{{ $sessionLabel }}</td>
                                                <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface">{{ $session['child_count'] }} anak</td>
                                                <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface text-right whitespace-nowrap">{{ $fmt($session['nominal']) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-surface-container-low">
                                        <tr>
                                            <td colspan="4" class="px-4 py-2.5 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total belum dibayar</td>
                                            <td class="px-4 py-2.5 font-label-md text-label-md text-on-surface text-right whitespace-nowrap">{{ $fmt($coach->total) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <p class="font-body-sm text-body-sm text-outline">Belum ada sesi yang belum dibayar.</p>
                        @endif

                        @if ($coach->payments->isNotEmpty())
                            <div class="mt-5">
                                <h4 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-2">Riwayat Pembayaran</h4>
                                <div class="overflow-x-auto rounded-lg border border-outline-variant/30">
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
                                            @foreach ($coach->payments as $payment)
                                                <tr class="hover:bg-surface-container-low/50 transition-colors">
                                                    <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface whitespace-nowrap">{{ $dateFmt($payment->paid_at) }}</td>
                                                    <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface">{{ $payment->session_count }} sesi</td>
                                                    <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface">{{ $payment->note ?? '-' }}</td>
                                                    <td class="px-4 py-2.5 font-body-sm text-body-sm text-on-surface text-right whitespace-nowrap">{{ $fmt($payment->amount) }}</td>
                                                    <td class="px-4 py-2.5 text-right">
                                                        <form action="{{ route('admin.salaries.payments.destroy', $payment) }}" method="POST"
                                                              onsubmit="return confirm('Hapus catatan pembayaran ini? Batch honor akan dibuka kembali.')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus">
                                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 p-10 text-center font-body-sm text-body-sm text-outline">
                    Belum ada pelatih terdaftar.
                </div>
            @endforelse
        </div>
    </div>
</x-sidebar-layout>
