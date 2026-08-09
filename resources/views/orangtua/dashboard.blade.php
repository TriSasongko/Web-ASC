<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Orang Tua</h2>
    </x-slot>

    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                Selamat datang, {{ auth()->user()->name }}!
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Program & Sisa Pertemuan Anak</h3>

                @forelse ($students as $student)
                    <div class="border rounded-lg p-3 mb-3">
                        <p class="font-semibold">{{ $student->full_name }}</p>
                        @forelse ($student->classes as $enrollment)
                            @php
                                $program = $enrollment->program;
                                $total = $program->total_sessions;
                                $left = $total === null ? null : max(0, $total - $enrollment->pivot->sessions_completed);
                            @endphp
                            <div class="ml-4 mt-1 text-sm flex items-center gap-2">
                                <span>{{ $enrollment->name }} — {{ $program->name }}</span>
                                @if ($left === null)
                                    <span class="text-gray-500">Bulanan</span>
                                @elseif ($left === 0)
                                    <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-700">Paket habis</span>
                                @elseif ($left <= 2)
                                    <span class="px-2 py-0.5 rounded text-xs bg-yellow-100 text-yellow-700">Sisa {{ $left }}x</span>
                                @else
                                    <span class="text-gray-500">Sisa {{ $left }}x</span>
                                @endif
                                <span class="text-gray-400">{{ $fmt($program->price) }}</span>
                            </div>
                        @empty
                            <p class="ml-4 text-sm text-gray-400">Belum ada kelas aktif.</p>
                        @endforelse
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada anak terdaftar.</p>
                @endforelse
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Rekomendasi Naik Kelas</h3>

                @forelse ($pendingRecommendations as $rec)
                    <div class="border rounded-lg p-3 mb-3">
                        <p>
                            <strong>{{ $rec->student->full_name }}</strong>
                            @if ($rec->currentClass)
                                — {{ $rec->currentClass->name }} (Level {{ $rec->currentClass->level ?? '-' }})
                            @endif
                            → <strong>{{ $rec->recommendedClass->name ?? 'Level '.($rec->recommended_level ?? '-') }}</strong>
                        </p>
                        <p class="text-sm text-gray-600 mt-1">Dari: {{ $rec->from->name }} ({{ $rec->from->isAdmin() ? 'Admin' : 'Pelatih' }})</p>
                        @if ($rec->note)
                            <p class="text-sm text-gray-500 mt-1">Catatan: {{ $rec->note }}</p>
                        @endif

                        <div class="mt-2 flex gap-2">
                            <form action="{{ route('orangtua.recommendations.respond', $rec) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="diterima">
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-md text-xs">Setuju</button>
                            </form>
                            <form action="{{ route('orangtua.recommendations.respond', $rec) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md text-xs">Tidak Setuju</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada rekomendasi.</p>
                @endforelse
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">E-Raport Anak</h3>
                @php
                    $myDevelopments = \App\Models\Development::whereHas('student', fn($q) => $q->where('parent_id', auth()->id()))
                        ->with('student')->latest()->get();
                @endphp
                @forelse ($myDevelopments as $dev)
                    <p class="mb-1">
                        {{ $dev->student->full_name }} — {{ $dev->period }}
                        <a href="{{ route('eraport.show', [$dev->student, $dev->id]) }}" class="text-indigo-600 ml-2">Lihat →</a>
                    </p>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada E-Raport tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-sidebar-layout>
