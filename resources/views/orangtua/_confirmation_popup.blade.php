@if ($regs->count())
    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
        $jam = (int) now()->format('G');
        $salam = match (true) {
            $jam >= 5 && $jam < 11 => 'pagi',
            $jam >= 11 && $jam < 15 => 'siang',
            $jam >= 15 && $jam < 18 => 'sore',
            default => 'malam',
        };
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
                            <p class="font-body-sm text-body-sm text-white/90 mt-1">Pendaftaran anak Anda menunggu verifikasi Admin. Konfirmasikan via WhatsApp lalu selesaikan pembayaran agar paket aktif.</p>
                        </div>
                        <button @click="{{ $dismissConfirm }}" type="button"
                            class="absolute top-3 right-3 inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-white/15 transition-colors" title="Tutup">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                </div>

                {{-- Status pendaftaran per siswa --}}
                <div class="px-6 py-5 overflow-y-auto space-y-3">
                    @foreach ($regs as $reg)
                        @php
                            $waText = "Selamat {$salam} Admin Antasena Swimming Club.\n\n"
                                . 'Saya ' . auth()->user()->name . ', orang tua/wali dari *' . ($reg->student?->full_name ?? '-') . '* yang telah mendaftar program *' . ($reg->program?->name ?? '-') . '* (biaya paket ' . $fmt($reg->program?->price) . ").\n\n"
                                . 'Mohon konfirmasi pendaftaran dan informasi pembayaran paketnya agar bisa segera diproses.' . "\n\n"
                                . 'Terima kasih atas bantuan dan informasinya. 🙏';
                            $waUrl = 'https://wa.me/62895609706131?text=' . rawurlencode($waText);
                        @endphp
                        <div class="rounded-xl border border-outline-variant/30 bg-surface/50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $reg->student?->full_name }}</p>
                                    <p class="font-body-sm text-body-sm text-outline truncate mt-0.5">{{ $reg->program?->name }}</p>
                                </div>
                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    Menunggu Verifikasi
                                </span>
                            </div>
                            <div class="mt-3 flex items-center justify-between gap-2 rounded-lg bg-surface-container-low px-3 py-2">
                                <span class="font-label-sm text-label-sm text-outline">Biaya Paket</span>
                                <span class="font-label-md text-label-md text-on-surface">{{ $fmt($reg->program?->price) }}</span>
                            </div>
                            <div class="mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <a href="{{ $waUrl }}" target="_blank" @click="open = false"
                                    class="inline-flex items-center justify-center gap-2 bg-[#25D366] text-white px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 shadow-sm">
                                    <span class="material-symbols-outlined text-[18px]">chat</span>
                                    Konfirmasi via WhatsApp
                                </a>
                                <p class="font-body-sm text-body-sm text-outline">Setelah Admin verifikasi & pembayaran diterima, paket otomatis aktif.</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Footer aksi --}}
                <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-outline-variant/30 bg-surface/50">
                    <p class="font-body-sm text-body-sm text-outline">Status lengkap ada di menu <span class="font-label-md text-label-md text-on-surface">Pendaftaran Anak Saya</span>.</p>
                    <button @click="{{ $dismissConfirm }}" type="button"
                        class="shrink-0 inline-flex items-center justify-center gap-2 border border-outline-variant/60 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
