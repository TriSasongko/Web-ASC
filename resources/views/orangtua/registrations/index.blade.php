<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Pendaftaran Anak Saya</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola pendaftaran dan konfirmasi status anak Anda.</p>
            </div>
            <a href="{{ route('orangtua.registrations.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Daftarkan Anak
            </a>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
            </div>
        @endif

        @php
            $jam = (int) now()->format('G');
            $salam = match (true) {
                $jam >= 5 && $jam < 11 => 'pagi',
                $jam >= 11 && $jam < 15 => 'siang',
                $jam >= 15 && $jam < 18 => 'sore',
                default => 'malam',
            };
        @endphp

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <h3 class="font-headline text-headline-sm text-on-surface">Daftar Pendaftaran</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Nama Anak</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Program</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Status</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($registrations as $reg)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $reg->student->full_name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $reg->program->name }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $badge = match($reg->status) {
                                            'diterima' => 'bg-[#E8F5E9] text-[#2E7D32]',
                                            'ditolak' => 'bg-error-container text-on-error-container',
                                            default => 'bg-[#FFF8E1] text-[#B26A00]',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm {{ $badge }}">
                                        {{ str_replace('_', ' ', ucfirst($reg->status)) }}
                                    </span>
                                </td>
                                @php
                                    $student = $reg->student;
                                    $gender = $student->gender === 'L' ? 'Laki-laki' : 'Perempuan';
                                    $ttl = implode(', ', array_filter([$student->birth_place, $student->birth_date?->format('d/m/Y')])) ?: '-';

                                    $waText = "Selamat {$salam} Admin Antasena Swimming Club.\n\n"
                                        . "Saya orang tua/wali dari calon peserta didik yang ingin mendaftarkan diri ke Antasena Swimming Club. Berikut data yang telah saya isi:\n\n"
                                        . "*Formulir Pendaftaran Antasena Swimming Club*\n\n"
                                        . "Nama : {$student->full_name}\n"
                                        . "TTL : {$ttl}\n"
                                        . "Jenis Kelamin : {$gender}\n"
                                        . "No. HP : " . (auth()->user()->phone ?: '-') . "\n"
                                        . "Alamat : " . ($student->address ?: '-') . "\n"
                                        . "Kelas/Program : {$reg->program->name}\n"
                                        . "BB : " . ($student->weight ?: '-') . " kg\n"
                                        . "TB : " . ($student->height ?: '-') . " cm\n\n"
                                        . "Mohon dibantu untuk proses pendaftarannya, Admin.\n\n"
                                        . "Terima kasih atas bantuan dan informasinya. 🙏";

                                    $waUrl = 'https://wa.me/62895609706131?text=' . rawurlencode($waText);
                                @endphp
                                <td class="px-4 py-3">
                                    @if ($reg->status === 'menunggu_verifikasi')
                                        <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#25D366] hover:opacity-90 text-white font-label-sm text-label-sm rounded-lg transition-all">
                                            <span class="material-symbols-outlined text-[16px]">chat</span>
                                            Konfirmasi via WhatsApp
                                        </a>
                                    @else
                                        <span class="font-label-sm text-label-sm text-outline">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center font-body-sm text-body-sm text-outline">Belum ada pendaftaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-outline-variant/30">{{ $registrations->links() }}</div>
        </div>
    </div>
</x-sidebar-layout>
