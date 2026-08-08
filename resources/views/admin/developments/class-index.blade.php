<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Perkembangan Siswa — {{ $class->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-500 mb-4">Program: {{ $class->program->name }} | Coach: {{ $class->coach->name }}</p>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $student->full_name }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('admin.classes.developments.create', [$class, $student]) }}" class="text-indigo-600">Isi Penilaian</a>
                                    <a href="{{ route('admin.classes.developments.history', [$class, $student]) }}" class="text-gray-600">Riwayat</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-6 text-center text-gray-500">Belum ada siswa aktif di kelas ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-sidebar-layout>
