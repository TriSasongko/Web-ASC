<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $class->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p><strong>Program:</strong> {{ $class->program->name }}</p>
                <p><strong>Coach:</strong> {{ $class->coach->name }}</p>
                <p><strong>Kapasitas:</strong> {{ $class->students->count() }}/{{ $class->capacity ?? '∞' }}</p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Jadwal Latihan</h3>

                <table class="w-full text-sm text-left mb-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Hari</th>
                            <th class="px-4 py-2">Jam</th>
                            <th class="px-4 py-2">Sesi</th>
                            <th class="px-4 py-2">Lokasi</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($class->schedules as $s)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ ucfirst($s->day) }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</td>
                                <td class="px-4 py-2">Sesi {{ $s->session_number }}</td>
                                <td class="px-4 py-2">{{ $s->location ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('admin.schedules.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada jadwal.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <form action="{{ route('admin.classes.schedules.store', $class) }}" method="POST" class="grid grid-cols-2 gap-3">
                    @csrf
                    <select name="day" class="border-gray-300 rounded-md" required>
                        <option value="">-- Hari --</option>
                        @foreach (['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $day)
                            <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="session_number" placeholder="Sesi ke-" min="1" value="1" class="border-gray-300 rounded-md" required>
                    <input type="time" name="start_time" class="border-gray-300 rounded-md" required>
                    <input type="time" name="end_time" class="border-gray-300 rounded-md" required>
                    <input type="text" name="location" placeholder="Lokasi (opsional)" class="border-gray-300 rounded-md col-span-2">
                    <button type="submit" class="col-span-2 px-4 py-2 bg-indigo-600 text-white rounded-md">+ Tambah Jadwal</button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Siswa di Kelas Ini</h3>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Rekap Pertemuan</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($class->students as $student)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $student->full_name }}</td>
                                <td class="px-4 py-2">{{ $student->pivot->sessions_completed }}/{{ $class->program->total_sessions ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('admin.class-students.remove', [$class, $student->id]) }}" method="POST"
                                          onsubmit="return confirm('Keluarkan siswa dari kelas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600">Keluarkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Belum ada siswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.classes.index') }}" class="text-gray-600">← Kembali</a>
        </div>
    </div>
</x-app-layout>
