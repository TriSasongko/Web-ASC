<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Pelatih</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama pelatih..."
                               class="border-gray-300 rounded-md shadow-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md">Cari</button>
                    </form>
                    <a href="{{ route('admin.coaches.create') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-md">+ Tambah Pelatih</a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Foto</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">No. HP</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coaches as $coach)
                            <tr class="border-b">
                                <td class="px-4 py-2">
                                    @if ($coach->photo)
                                        <img src="{{ Storage::url($coach->photo) }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200"></div>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $coach->name }}</td>
                                <td class="px-4 py-2">{{ $coach->email }}</td>
                                <td class="px-4 py-2">{{ $coach->phone ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs {{ $coach->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $coach->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('admin.coaches.edit', $coach) }}" class="text-indigo-600">Edit</a>

                                    <form action="{{ route('admin.coaches.toggle-active', $coach) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-yellow-600">{{ $coach->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>

                                    <form action="{{ route('admin.coaches.reset-password', $coach) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Reset password ke default?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-blue-600">Reset Password</button>
                                    </form>

                                    <form action="{{ route('admin.coaches.destroy', $coach) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus pelatih ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data pelatih.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $coaches->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
