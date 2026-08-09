<x-sidebar-layout>
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

            @if ($alerts->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">📌 Paket Perlu Konfirmasi ({{ $needConfirmationCount }})</h3>

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Kelas</th>
                                <th class="px-4 py-2">Jumlah</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alerts as $alert)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $alert->class_name }}</td>
                                    <td class="px-4 py-2">{{ $alert->total }} siswa</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.classes.show', $alert->class_id) }}"
                                           class="px-3 py-1 bg-indigo-600 text-white rounded-md text-xs">
                                            Konfirmasi →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-sidebar-layout>
