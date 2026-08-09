<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $class->name }}</h2>
    </x-slot>

    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
        $remaining = fn ($e) => $e->remainingSessions();
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p><strong>Program:</strong> {{ $class->program->name }} ({{ $fmt($class->program->price) }})</p>
                <p><strong>Level:</strong> {{ $class->level ? 'Level '.$class->level : '-' }}</p>
                <p><strong>Coach:</strong> {{ $class->coach->name }}</p>
                <p><strong>Kapasitas:</strong> {{ $enrollments->count() }}/{{ $class->capacity ?? '∞' }}</p>
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
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold">Siswa di Kelas Ini</h3>
                    <a href="{{ route('admin.classes.developments.index', $class) }}" class="text-indigo-600 text-sm">Perkembangan Siswa →</a>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Paket + Harga</th>
                            <th class="px-4 py-2">Pertemuan</th>
                            <th class="px-4 py-2">Status Paket</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $e)
                            @php
                                $student = $e->student;
                                $program = $e->schoolClass->program;
                                $parent = $student->parent;
                                $left = $remaining($e);
                                $phone = preg_replace('/\D/', '', $parent->phone ?? '');
                                $wa = 'https://wa.me/'.preg_replace('/^0/', '62', $phone);
                            @endphp
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $student->full_name }}</td>
                                <td class="px-4 py-2">{{ $program->name }}<br><span class="text-gray-500">{{ $fmt($program->price) }}</span></td>
                                <td class="px-4 py-2">{{ $e->sessions_completed }}/{{ $program->total_sessions ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if ($left === null)
                                        <span class="px-2 py-1 rounded text-xs bg-slate-100 text-slate-600">Bulanan</span>
                                    @elseif ($left >= 2)
                                        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Aman (sisa {{ $left }}x)</span>
                                    @elseif ($left === 1)
                                        <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">Sisa 1 pertemuan</span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Paket habis</span>
                                    @endif
                                    @if ($e->renewal_status === 'lanjut')
                                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700 ml-1">Lanjut</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 space-x-2">
                                    @if ($left !== null && $left <= 1)
                                        <div x-data="{ open: false }" class="relative inline-block">
                                            <button @click="open = ! open" type="button"
                                                class="px-3 py-1 bg-indigo-600 text-white rounded-md text-xs">
                                                Konfirmasi
                                            </button>

                                            <div x-show="open" @click.outside="open = false" x-transition
                                                class="absolute right-0 z-20 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg p-4">
                                                <p class="font-semibold text-gray-800">{{ $student->full_name }}</p>
                                                <p class="text-sm text-gray-600">Paket: {{ $program->name }} — {{ $fmt($program->price) }}</p>
                                                <p class="text-sm text-gray-600">Pertemuan: {{ $e->sessions_completed }}/{{ $program->total_sessions }} (sisa {{ $left }}x)</p>
                                                <p class="text-sm text-gray-600 mb-3">Orang Tua: {{ $parent->name }} ({{ $parent->phone ?? '-' }})</p>

                                                @if ($phone)
                                                    <a href="{{ $wa }}?text={{ urlencode('Halo '.$parent->name.', paket '.$program->name.' an. '.$student->full_name.' tersisa '.$left.' pertemuan lagi. Harga '.$fmt($program->price).'. Apakah ingin memperpanjang paket?') }}"
                                                       target="_blank" class="block text-center px-3 py-2 bg-green-600 text-white rounded-md text-xs mb-3">
                                                        Konfirmasi via WA
                                                    </a>
                                                @else
                                                    <p class="text-gray-400 text-xs mb-3">No. HP orang tua belum diisi.</p>
                                                @endif

                                                <form action="{{ route('admin.class-students.renew', $e) }}" method="POST" class="space-y-2">
                                                    @csrf @method('PATCH')
                                                    <input type="text" name="renewal_note" placeholder="Catatan (opsional)" class="w-full border-gray-300 rounded-md text-xs">
                                                    <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white rounded-md text-xs">Perpanjang Paket</button>
                                                </form>

                                                <form action="{{ route('admin.class-students.stop', $e) }}" method="POST" class="mt-2 space-y-2"
                                                      onsubmit="return confirm('Tandai '.$student->full_name.' sebagai BERHENTI?')">
                                                    @csrf @method('PATCH')
                                                    <input type="text" name="renewal_note" placeholder="Alasan berhenti (opsional)" class="w-full border-gray-300 rounded-md text-xs">
                                                    <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white rounded-md text-xs">Tandai Berhenti</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button @click="open = ! open" type="button"
                                            class="px-3 py-1 bg-amber-500 text-white rounded-md text-xs">
                                            Rekomendasi ↑
                                        </button>
                                        <div x-show="open" @click.outside="open = false" x-transition
                                            class="absolute right-0 z-20 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg p-4">
                                            <p class="font-semibold text-gray-800 mb-2">Rekomendasi Naik Kelas — {{ $student->full_name }}</p>
                                            <form action="{{ route('admin.recommendations.store') }}" method="POST" class="space-y-2">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                <input type="hidden" name="current_class_id" value="{{ $class->id }}">
                                                <select name="recommended_class_id" class="w-full border-gray-300 rounded-md text-xs">
                                                    <option value="">-- Kelas target (opsional) --</option>
                                                    @foreach ($candidateClasses as $c)
                                                        <option value="{{ $c->id }}">{{ $c->name }} (Level {{ $c->level ?? '-' }})</option>
                                                    @endforeach
                                                </select>
                                                <input type="number" min="1" name="recommended_level" placeholder="Atau level target (opsional)"
                                                    class="w-full border-gray-300 rounded-md text-xs">
                                                <textarea name="note" rows="2" placeholder="Catatan (opsional)" class="w-full border-gray-300 rounded-md text-xs"></textarea>
                                                <button type="submit" class="w-full px-3 py-2 bg-amber-500 text-white rounded-md text-xs">Simpan Rekomendasi</button>
                                            </form>
                                        </div>
                                    </div>

                                    <a href="{{ route('admin.classes.developments.history', [$class, $student]) }}" class="text-indigo-600">Perkembangan</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada siswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.classes.index') }}" class="text-gray-600">← Kembali</a>
        </div>
    </div>
</x-sidebar-layout>
