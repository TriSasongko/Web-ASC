<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Orang Tua</h2>
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
                               placeholder="Cari nama orang tua..."
                               class="border-gray-300 rounded-md shadow-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md">Cari</button>
                    </form>
                    <a href="{{ route('admin.parents.create') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-md">+ Tambah Orang Tua</a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">No. HP</th>
                            <th class="px-4 py-2">Alamat</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parents as $parent)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $parent->name }}</td>
                                <td class="px-4 py-2">{{ $parent->email }}</td>
                                <td class="px-4 py-2">{{ $parent->phone ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $parent->address ?? '-' }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('admin.parents.edit', $parent) }}" class="text-indigo-600">Edit</a>

                                    <form action="{{ route('admin.parents.reset-password', $parent) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Reset password ke default?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-blue-600">Reset Password</button>
                                    </form>

                                    <form action="{{ route('admin.parents.destroy', $parent) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus orang tua ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada data orang tua.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $parents->links() }}</div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
