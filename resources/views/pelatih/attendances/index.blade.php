<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Absensi — Kelas Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Kelas</th>
                            <th class="px-4 py-2">Program</th>
                            <th class="px-4 py-2">Jumlah Siswa Aktif</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $class->name }}</td>
                                <td class="px-4 py-2">{{ $class->program->name }}</td>
                                <td class="px-4 py-2">{{ $class->students()->wherePivot('is_active', true)->count() }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('pelatih.attendances.create', $class) }}" class="text-indigo-600">Ambil Absensi</a>
                                    <a href="{{ route('pelatih.developments.index', $class) }}" class="text-green-600">Perkembangan</a>
                                    <a href="{{ route('pelatih.attendances.history', $class) }}" class="text-gray-600">Riwayat</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Anda belum memiliki kelas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-sidebar-layout>
