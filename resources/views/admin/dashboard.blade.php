<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                Selamat datang, {{ auth()->user()->name }}! Anda login sebagai Admin.
            </div>

            @if ($packageAlerts->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">⚠️ Paket Hampir Habis / Habis</h3>

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Nama Siswa</th>
                                <th class="px-4 py-2">Kelas</th>
                                <th class="px-4 py-2">Sisa Pertemuan</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Orang Tua</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packageAlerts as $alert)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $alert->student_name }}</td>
                                    <td class="px-4 py-2">{{ $alert->class_name }} ({{ $alert->program_name }})</td>
                                    <td class="px-4 py-2">{{ $alert->sessions_completed }}/{{ $alert->total_sessions }}</td>
                                    <td class="px-4 py-2">
                                        @if ($alert->sessions_completed >= $alert->total_sessions)
                                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Habis</span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">Sisa 1x</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $alert->parent_name }}</td>
                                    <td class="px-4 py-2">
                                        @if ($alert->parent_phone)
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $alert->parent_phone)) }}?text={{ urlencode('Halo '.$alert->parent_name.', paket renang '.$alert->student_name.' tersisa '.($alert->total_sessions - $alert->sessions_completed).' pertemuan lagi. Apakah ingin memperpanjang paket?') }}"
                                               target="_blank" class="px-3 py-1 bg-green-600 text-white rounded-md text-xs">
                                                Konfirmasi via WA
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">No. HP belum diisi</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
