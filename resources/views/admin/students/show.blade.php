<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekap — {{ $student->full_name }}</h2>
    </x-slot>

    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-2">Data Orang Tua</h3>
                <p>Nama: {{ $student->parent->name }}</p>
                <p>
                    No. HP:
                    @if ($student->parent->phone)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $student->parent->phone)) }}"
                           target="_blank" class="text-green-600 underline">
                            {{ $student->parent->phone }} (Chat WA)
                        </a>
                    @else
                        -
                    @endif
                </p>
            </div>

            @forelse ($student->classes as $class)
                @php
                    $completed = $class->pivot->sessions_completed;
                    $total = $class->program->total_sessions;
                    $isPaket = $class->program->billing_type === 'per_paket';
                    $left = $total === null ? null : max(0, $total - $completed);
                    $status = null;
                    if ($isPaket && $total) {
                        if ($completed >= $total) {
                            $status = ['label' => 'Paket Habis', 'color' => 'bg-red-100 text-red-700'];
                        } elseif ($completed == $total - 1) {
                            $status = ['label' => 'Hampir Habis (sisa 1x)', 'color' => 'bg-yellow-100 text-yellow-700'];
                        }
                    }
                @endphp

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-700">{{ $class->name }} — {{ $class->program->name }}</h3>
                            <p class="text-sm text-gray-500">Coach: {{ $class->coach->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">
                                @if ($isPaket)
                                    {{ $completed }}/{{ $total ?? '∞' }} pertemuan
                                @else
                                    Kompetitif (bulanan)
                                @endif
                            </p>
                            @if ($status)
                                <span class="px-2 py-1 rounded text-xs {{ $status['color'] }}">{{ $status['label'] }}</span>
                            @endif
                        </div>
                    </div>

                    @php
                        $monthNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                    @endphp

                    <div class="mt-2">
                        @if ($isPaket && $total)
                            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                                @foreach (($classGrids[$class->id] ?? []) as $cell)
                                    @php
                                        $cellClass = $cell['attended'] ? 'bg-green-100 border-green-200' : 'bg-white border-dashed border-gray-300';
                                    @endphp
                                    <div class="rounded-lg border p-2 text-center {{ $cellClass }}">
                                        <p class="text-xs font-semibold text-gray-600">Pert {{ $cell['number'] }}</p>
                                        @if ($cell['attended'])
                                            <p class="text-xs font-medium text-green-700">Hadir</p>
                                            <p class="text-xs text-gray-500">{{ $cell['date']->format('d/m') }}</p>
                                        @else
                                            <p class="text-xs text-gray-400">Belum</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @foreach (($monthlyGrids[$class->id] ?? []) as $monthly)
                                <p class="text-sm font-medium text-gray-600 mb-2">
                                    {{ $monthNames[$monthly['month']->month] }} {{ $monthly['month']->year }}
                                </p>
                                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2 mb-3">
                                    @foreach ($monthly['cells'] as $cell)
                                        @php
                                            $cellClass = $cell['attended'] ? 'bg-green-100 border-green-200' : 'bg-white border-dashed border-gray-300';
                                        @endphp
                                        <div class="rounded-lg border p-2 text-center {{ $cellClass }}">
                                            <p class="text-xs font-semibold text-gray-600">{{ $cell['date']->format('d/m') }}</p>
                                            @if ($cell['attended'])
                                                <p class="text-xs font-medium text-green-700">Hadir</p>
                                            @else
                                                <p class="text-xs text-gray-400">Belum</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif

                        <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-gray-500">
                            <span class="inline-flex items-center gap-1">
                                <span class="h-3 w-3 rounded-sm bg-green-100 border border-green-200"></span> Hadir
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <span class="h-3 w-3 rounded-sm bg-white border border-dashed border-gray-300"></span> Belum Absen
                            </span>
                        </div>
                    </div>

                    @if ($isPaket && $total && $left !== null)
                        @php
                            $pivot = $class->pivot;
                            $phone = preg_replace('/\D/', '', $student->parent->phone ?? '');
                            $wa = 'https://wa.me/'.preg_replace('/^0/', '62', $phone);
                        @endphp
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            @if ($pivot->renewal_status === 'berhenti')
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Berhenti</span>
                                    <form action="{{ route('admin.class-students.activate', $pivot->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-md text-xs">Aktifkan Kembali</button>
                                    </form>
                                </div>
                            @elseif ($pivot->renewal_status === 'lanjut')
                                <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">Lanjut</span>
                            @elseif ($left <= 1)
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = ! open" type="button"
                                        class="px-3 py-1 {{ $left === 0 ? 'bg-red-600' : 'bg-yellow-600' }} text-white rounded-md text-xs">
                                        {{ $left === 0 ? 'Paket habis — Konfirmasi' : 'Sisa '.$left.'x — Konfirmasi' }}
                                    </button>

                                    <div x-show="open" @click.outside="open = false" x-transition
                                        class="absolute left-0 z-20 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg p-4">
                                        <p class="font-semibold text-gray-800">{{ $student->full_name }}</p>
                                        <p class="text-sm text-gray-600">Paket: {{ $class->program->name }} — {{ $fmt($class->program->price) }}</p>
                                        <p class="text-sm text-gray-600">Pertemuan: {{ $pivot->sessions_completed }}/{{ $total }} (sisa {{ $left }}x)</p>
                                        <p class="text-sm text-gray-600 mb-3">Orang Tua: {{ $student->parent->name }} ({{ $student->parent->phone ?? '-' }})</p>

                                        @if ($phone)
                                            <a href="{{ $wa }}?text={{ urlencode('Halo '.$student->parent->name.', paket '.$class->program->name.' an. '.$student->full_name.' tersisa '.$left.' pertemuan lagi. Harga '.$fmt($class->program->price).'. Apakah ingin memperpanjang paket?') }}"
                                               target="_blank" class="block text-center px-3 py-2 bg-green-600 text-white rounded-md text-xs mb-3">
                                                Konfirmasi via WA
                                            </a>
                                        @else
                                            <p class="text-gray-400 text-xs mb-3">No. HP orang tua belum diisi.</p>
                                        @endif

                                        <form action="{{ route('admin.class-students.renew', $pivot->id) }}" method="POST" class="space-y-2">
                                            @csrf @method('PATCH')
                                            <input type="text" name="renewal_note" placeholder="Catatan (opsional)" class="w-full border-gray-300 rounded-md text-xs">
                                            <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white rounded-md text-xs">Perpanjang Paket</button>
                                        </form>

                                        <form action="{{ route('admin.class-students.stop', $pivot->id) }}" method="POST" class="mt-2 space-y-2"
                                              onsubmit="return confirm('Tandai '.$student->full_name.' sebagai BERHENTI?')">
                                            @csrf @method('PATCH')
                                            <input type="text" name="renewal_note" placeholder="Alasan berhenti (opsional)" class="w-full border-gray-300 rounded-md text-xs">
                                            <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white rounded-md text-xs">Tandai Berhenti</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Siswa ini belum ditempatkan di kelas manapun.
                </div>
            @endforelse

            <a href="{{ route('admin.students.index') }}" class="text-gray-600">← Kembali</a>
        </div>
    </div>
</x-sidebar-layout>
