<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekap — {{ $student->full_name }}</h2>
    </x-slot>

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

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Hari</th>
                                <th class="px-4 py-2">Sesi</th>
                                <th class="px-4 py-2">Pertemuan Ke-</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $meetingCount = 0; @endphp
                            @forelse (($attendances[$class->id] ?? collect())->sortBy('attendance_date') as $a)
                                @if ($a->status === 'hadir') @php $meetingCount++; @endphp @endif
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $a->attendance_date->format('d-m-Y') }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($a->attendance_date)->translatedFormat('l') }}</td>
                                    <td class="px-4 py-2">Sesi {{ $a->session_number }}</td>
                                    <td class="px-4 py-2">{{ $a->status === 'hadir' ? $meetingCount.'/'.($total ?? '∞') : '-' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded text-xs {{ $a->status === 'hadir' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $a->status === 'hadir' ? 'Hadir' : 'Tidak Hadir' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada absensi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                    Siswa ini belum ditempatkan di kelas manapun.
                </div>
            @endforelse

            <a href="{{ route('admin.students.index') }}" class="text-gray-600">← Kembali</a>
        </div>
    </div>
</x-app-layout>
