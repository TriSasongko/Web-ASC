<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Kelas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('admin.class-students.unplaced') }}" class="text-indigo-600">
                        Lihat siswa belum ditempatkan →
                    </a>
                    <a href="{{ route('admin.classes.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md">+ Tambah Kelas</a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Kelas</th>
                            <th class="px-4 py-2">Program</th>
                            <th class="px-4 py-2">Coach</th>
                            <th class="px-4 py-2">Jadwal</th>
                            <th class="px-4 py-2">Kapasitas</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $class->name }}</td>
                                <td class="px-4 py-2">{{ $class->program->name }}</td>
                                <td class="px-4 py-2">{{ $class->coach->name }}</td>
                                <td class="px-4 py-2 text-xs">
                                    @forelse ($class->schedules as $s)
                                        <div>{{ ucfirst($s->day) }}, {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} WIB</div>
                                    @empty
                                        <span class="text-gray-400">Belum ada jadwal</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-2">{{ $class->students()->count() }}/{{ $class->capacity ?? '∞' }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('admin.classes.show', $class) }}" class="text-indigo-600">Detail</a>
                                    <a href="{{ route('admin.classes.edit', $class) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus kelas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada kelas.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $classes->links() }}</div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
