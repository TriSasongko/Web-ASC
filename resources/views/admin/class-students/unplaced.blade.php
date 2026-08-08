<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Siswa Belum Ditempatkan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Program</th>
                            <th class="px-4 py-2">Tempatkan ke Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $reg)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $reg->student->full_name }}</td>
                                <td class="px-4 py-2">{{ $reg->program->name }}</td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('admin.class-students.place', $reg) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <select name="class_id" class="border-gray-300 rounded-md" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach ($reg->program->classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->coach->name }})</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-md text-xs">Tempatkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Tidak ada siswa yang perlu ditempatkan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.classes.index') }}" class="text-gray-600">← Kembali ke Kelas</a>
        </div>
    </div>
</x-sidebar-layout>
