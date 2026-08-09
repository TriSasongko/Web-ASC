<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Perkembangan Siswa — {{ $class->name }}</h2>
    </x-slot>

    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-4">
                    Program: {{ $class->program->name }} ({{ $fmt($class->program->price) }}) · Level {{ $class->level ?? '-' }}
                </p>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Pertemuan</th>
                            <th class="px-4 py-2">Status Paket</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            @php
                                $total = $class->program->total_sessions;
                                $left = $total === null ? null : max(0, $total - $student->pivot->sessions_completed);
                            @endphp
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $student->full_name }}</td>
                                <td class="px-4 py-2">{{ $student->pivot->sessions_completed }}/{{ $total ?? '-' }}</td>
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
                                    @if ($student->pivot->renewal_status === 'lanjut')
                                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700 ml-1">Lanjut</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('pelatih.developments.create', [$class, $student]) }}" class="text-indigo-600">Isi Penilaian</a>
                                    <a href="{{ route('pelatih.developments.history', [$class, $student]) }}" class="text-gray-600">Riwayat</a>

                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button @click="open = ! open" type="button"
                                            class="px-2 py-1 bg-amber-500 text-white rounded-md text-xs">
                                            Rekomendasi ↑
                                        </button>
                                        <div x-show="open" @click.outside="open = false" x-transition
                                            class="absolute right-0 z-20 mt-2 w-72 bg-white border border-gray-200 rounded-lg shadow-lg p-4">
                                            <p class="font-semibold text-gray-800 mb-2">Rekomendasi Naik Kelas — {{ $student->full_name }}</p>
                                            <form action="{{ route('pelatih.recommendations.store', [$class, $student]) }}" method="POST" class="space-y-2">
                                                @csrf
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
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada siswa aktif di kelas ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-sidebar-layout>
