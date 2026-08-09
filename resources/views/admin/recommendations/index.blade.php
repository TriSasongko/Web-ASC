<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekomendasi Naik Kelas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Siswa</th>
                            <th class="px-4 py-2">Kelas Saat Ini</th>
                            <th class="px-4 py-2">Target</th>
                            <th class="px-4 py-2">Dari</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recommendations as $rec)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $rec->student->full_name }}</td>
                                <td class="px-4 py-2">{{ $rec->currentClass->name ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    {{ $rec->recommendedClass->name ?? 'Level '.($rec->recommended_level ?? '-') }}
                                </td>
                                <td class="px-4 py-2">{{ $rec->from->name }} ({{ $rec->from->isAdmin() ? 'Admin' : 'Pelatih' }})</td>
                                <td class="px-4 py-2">
                                    @if ($rec->status === 'pending')
                                        <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">Menunggu respon orang tua</span>
                                    @elseif ($rec->status === 'diterima')
                                        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Disetujui orang tua</span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 space-x-2">
                                    @if ($rec->status === 'diterima')
                                        <a href="{{ route('admin.classes.show', $rec->currentClass ?? $rec->recommendedClass ?? $rec->student->classes()->first()) }}"
                                           class="text-indigo-600">Pindahkan</a>
                                    @endif
                                    <form action="{{ route('admin.recommendations.destroy', $rec) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus rekomendasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada rekomendasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $recommendations->links() }}</div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
