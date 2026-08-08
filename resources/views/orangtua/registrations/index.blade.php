<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pendaftaran Anak Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-end mb-4">
                    <a href="{{ route('orangtua.registrations.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                        + Daftarkan Anak
                    </a>
                </div>

                @php
                    $jam = (int) now()->format('G');
                    $salam = match (true) {
                        $jam >= 5 && $jam < 11 => 'pagi',
                        $jam >= 11 && $jam < 15 => 'siang',
                        $jam >= 15 && $jam < 18 => 'sore',
                        default => 'malam',
                    };
                @endphp

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Anak</th>
                            <th class="px-4 py-2">Program</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $reg)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $reg->student->full_name }}</td>
                                <td class="px-4 py-2">{{ $reg->program->name }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $badge = match($reg->status) {
                                            'diterima' => 'bg-green-100 text-green-700',
                                            'ditolak' => 'bg-red-100 text-red-700',
                                            default => 'bg-yellow-100 text-yellow-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs {{ $badge }}">
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
                                <td class="px-4 py-2">
                                    @if ($reg->status === 'menunggu_verifikasi')
                                        <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-md">
                                            Konfirmasi via WhatsApp
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada pendaftaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $registrations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
