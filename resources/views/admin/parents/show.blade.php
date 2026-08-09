<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Orang Tua — {{ $parent->name }}</h2>
    </x-slot>

    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p><strong>Nama:</strong> {{ $parent->name }}</p>
                <p><strong>Email:</strong> {{ $parent->email }}</p>
                <p><strong>No. HP:</strong> {{ $parent->phone ?? '-' }}</p>
                <p><strong>Status:</strong>
                    @if ($parent->is_active)
                        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Aktif</span>
                    @else
                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Nonaktif</span>
                    @endif
                </p>
                <div class="mt-3 flex gap-2">
                    <form action="{{ route('admin.parents.toggle-active', $parent) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-4 py-2 {{ $parent->is_active ? 'bg-red-600' : 'bg-green-600' }} text-white rounded-md text-sm">
                            {{ $parent->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.parents.edit', $parent) }}" class="px-4 py-2 bg-gray-200 rounded-md text-sm">Edit</a>
                    <a href="{{ route('admin.parents.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-sm">← Kembali</a>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Anak & Enrolment</h3>

                @forelse ($students as $student)
                    <div class="border rounded-lg p-4 mb-4">
                        <p class="font-semibold">{{ $student->full_name }}
                            @if ($student->nickname)
                                <span class="text-gray-500 text-sm">({{ $student->nickname }})</span>
                            @endif
                        </p>

                        @forelse ($student->classes as $enrollment)
                            @php
                                $program = $enrollment->program;
                                $total = $program->total_sessions;
                                $left = $total === null ? null : max(0, $total - $enrollment->pivot->sessions_completed);
                                $phone = preg_replace('/\D/', '', $parent->phone ?? '');
                                $wa = 'https://wa.me/'.preg_replace('/^0/', '62', $phone);
                            @endphp
                            <div class="ml-4 mt-2 flex flex-wrap items-center gap-2 text-sm">
                                <span class="text-gray-700">{{ $enrollment->name }} ({{ $program->name }} · {{ $fmt($program->price) }})</span>
                                <span class="text-gray-500">{{ $enrollment->pivot->sessions_completed }}/{{ $total ?? '-' }} pertemuan</span>

                                @if ($enrollment->pivot->renewal_status === 'berhenti')
                                    <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Berhenti</span>
                                @elseif ($left !== null && $left === 0)
                                    <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Habis</span>
                                @elseif ($left !== null && $left === 1)
                                    <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">Sisa 1x</span>
                                @elseif ($enrollment->pivot->renewal_status === 'lanjut')
                                    <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">Lanjut</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Aman</span>
                                @endif

                                <div class="space-x-2 ml-auto">
                                    @if ($enrollment->pivot->renewal_status === 'berhenti')
                                        <form action="{{ route('admin.class-students.activate', $enrollment->pivot->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-green-600">Aktifkan</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.class-students.renew', $enrollment->pivot->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-blue-600">Perpanjang</button>
                                        </form>
                                        @if ($phone && $left !== null && $left <= 1)
                                            <a href="{{ $wa }}?text={{ urlencode('Halo '.$parent->name.', paket '.$program->name.' an. '.$student->full_name.' tersisa '.$left.' pertemuan lagi. Harga '.$fmt($program->price).'. Apakah ingin memperpanjang paket?') }}"
                                               target="_blank" class="text-green-600">WA</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="ml-4 text-sm text-gray-400">Belum ada enrolment di kelas manapun.</p>
                        @endforelse
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada anak terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-sidebar-layout>
