@if ($reg)
    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
        $jam = (int) now()->format('G');
        $salam = match (true) {
            $jam >= 5 && $jam < 11 => 'pagi',
            $jam >= 11 && $jam < 15 => 'siang',
            $jam >= 15 && $jam < 18 => 'sore',
            default => 'malam',
        };
        $waText = "Selamat {$salam} Admin Antasena Swimming Club.\n\n"
            . 'Saya ' . auth()->user()->name . ', orang tua/wali dari *' . ($reg->student?->full_name ?? '-') . '* yang telah mendaftar program *' . ($reg->program?->name ?? '-') . '* (biaya paket ' . $fmt($reg->program?->price) . ").\n\n"
            . 'Mohon konfirmasi pendaftaran dan informasi pembayaran paketnya agar bisa segera diproses.' . "\n\n"
            . 'Terima kasih atas bantuan dan informasinya. 🙏';
        $waUrl = 'https://wa.me/62895609706131?text=' . rawurlencode($waText);
        $dismissConfirm = 'open = false';
    @endphp
    <div x-data="{ open: true }">
        <div x-show="open" x-cloak x-transition.opacity
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
            @click="{{ $dismissConfirm }}"></div>

        <div x-show="open" x-cloak x-transition
            @keydown.escape.window="open = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden max-h-[88vh] flex flex-col">
                {{-- Header hijau WhatsApp --}}
                <div class="relative bg-[#25D366] text-white px-6 py-5 overflow-hidden">
                    <div class="flex items-start gap-4 relative">
                        <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[26px]">verified_user</span>
                        </div>
                        <div class="min-w-0 pr-8">
                            <h3 class="font-headline text-headline-sm">Konfirmasi Pendaftaran & Pembayaran</h3>
                            <p class="font-body-sm text-body-sm text-white/90 mt-1">Untuk mengaktifkan paket anak Anda, konfirmasikan pendaftaran ke Admin lalu selesaikan pembayaran.</p>
                        </div>
                        <button @click="{{ $dismissConfirm }}" type="button"
                            class="absolute top-3 right-3 inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-white/15 transition-colors" title="Tutup">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                </div>

                {{-- Info pendaftaran --}}
                <div class="px-6 py-5 overflow-y-auto space-y-4">
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-outline-variant/30 bg-surface/50 px-4 py-3">
                        <div class="min-w-0">
                            <p class="font-label-md text-label-md text-on-surface truncate">{{ $reg->student?->full_name }}</p>
                            <p class="font-body-sm text-body-sm text-outline truncate mt-0.5">{{ $reg->program?->name }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-label-sm text-label-sm text-outline">Biaya Paket</p>
                            <p class="font-label-md text-label-md text-on-surface">{{ $fmt($reg->program?->price) }}</p>
                        </div>
                    </div>

                    @foreach ([
                        [1, 'Konfirmasi ke Admin', 'Klik tombol Konfirmasi via WhatsApp lalu kirim pesan otomatis ke Admin Antasena Swimming Club.'],
                        [2, 'Selesaikan Pembayaran', 'Bayar biaya paket sesuai informasi dari Admin. Setelah terverifikasi, paket anak Anda otomatis aktif.'],
                    ] as [$step, $title, $desc])
                        <div class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-full bg-[#E8F5E9] text-[#1B5E20] flex items-center justify-center font-label-md text-label-md shrink-0">{{ $step }}</span>
                            <div class="min-w-0">
                                <p class="font-label-md text-label-md text-on-surface">{{ $title }}</p>
                                <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Footer aksi --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-t border-outline-variant/30 bg-surface/50">
                    <a href="{{ $waUrl }}" target="_blank" @click="{{ $dismissConfirm }}"
                        class="inline-flex items-center justify-center gap-2 bg-[#25D366] text-white px-5 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                        Konfirmasi via WhatsApp
                    </a>
                    <a href="{{ route('orangtua.registrations.index') }}" @click="{{ $dismissConfirm }}"
                        class="inline-flex items-center justify-center gap-2 border border-outline-variant/60 text-on-surface-variant px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                        <span class="material-symbols-outlined text-[18px]">list_alt</span>
                        Lihat Status Pendaftaran
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
