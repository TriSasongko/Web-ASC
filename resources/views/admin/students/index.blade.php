<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Siswa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="mb-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama siswa..." class="border-gray-300 rounded-md shadow-sm">
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md">Cari</button>
                </form>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Orang Tua</th>
                            <th class="px-4 py-2">No. HP</th>
                            <th class="px-4 py-2">Paket</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $student->full_name }}</td>
                                <td class="px-4 py-2">{{ $student->parent->name }}</td>
                                <td class="px-4 py-2">{{ $student->parent->phone ?? '-' }}</td>
                                <td class="px-4 py-2 space-y-1">
                                    @forelse ($student->classes as $class)
                                        @if ($class->pivot->is_active)
                                            @php
                                                $total = $class->program->total_sessions;
                                                $left = $total === null ? null : max(0, $total - $class->pivot->sessions_completed);
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-700">{{ $class->name }}</span>
                                                <span class="text-gray-500 text-xs">{{ $class->pivot->sessions_completed }}/{{ $total ?? '-' }}</span>
                                                @if ($class->pivot->renewal_status === 'lanjut')
                                                    <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700">Lanjut</span>
                                                @elseif ($left === null)
                                                    <span class="px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-600">Bulanan</span>
                                                @elseif ($left === 0)
                                                    <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-700">Paket habis</span>
                                                @elseif ($left <= 1)
                                                    <span class="px-2 py-0.5 rounded text-xs bg-yellow-100 text-yellow-700">Sisa {{ $left }}x</span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Sisa {{ $left }}x</span>
                                                @endif
                                            </div>
                                        @endif
                                    @empty
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.students.show', $student) }}" class="text-indigo-600">Lihat Rekap</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada data siswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $students->links() }}</div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
