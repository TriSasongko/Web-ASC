<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Perkembangan Siswa — Semua</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="mb-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." class="border-gray-300 rounded-md">
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md">Cari</button>
                </form>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Siswa</th>
                            <th class="px-4 py-2">Kelas</th>
                            <th class="px-4 py-2">Coach</th>
                            <th class="px-4 py-2">Periode</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($developments as $dev)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $dev->student->full_name }}</td>
                                <td class="px-4 py-2">{{ $dev->schoolClass->name }}</td>
                                <td class="px-4 py-2">{{ $dev->coach->name }}</td>
                                <td class="px-4 py-2">{{ $dev->period }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('eraport.show', [$dev->student, $dev->id]) }}" class="text-indigo-600">Lihat E-Raport</a>
                                    <form action="{{ route('admin.developments.destroy', $dev) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $developments->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
