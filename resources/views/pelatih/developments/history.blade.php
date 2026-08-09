<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Penilaian — {{ $student->full_name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse ($developments as $dev)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold">{{ $dev->period }}</h3>
                        <a href="{{ route('eraport.show', [$student, $dev->id]) }}" class="text-indigo-600 text-sm">Lihat E-Raport →</a>
                    </div>
                    <p class="font-medium text-gray-600 text-sm mt-2">Penilaian Umum</p>
                    <div class="grid grid-cols-2 gap-2 text-sm mt-1">
                        @foreach (\App\Models\Development::umumAspects() as $key => $label)
                            <p>{{ $label }}: <strong>{{ \App\Models\Development::scoreLabel($dev->$key) }}</strong></p>
                        @endforeach
                    </div>
                    <p class="font-medium text-gray-600 text-sm mt-3">Penilaian Aspek Khusus</p>
                    <div class="grid grid-cols-2 gap-2 text-sm mt-1">
                        @foreach (\App\Models\Development::khususAspects() as $key => $label)
                            <p>{{ $label }}: <strong>{{ \App\Models\Development::scoreLabel($dev->$key) }}</strong></p>
                        @endforeach
                    </div>
                    @if ($dev->coach_note)
                        <p class="text-sm text-gray-600 mt-2">Catatan: {{ $dev->coach_note }}</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">Belum ada penilaian untuk siswa ini.</p>
            @endforelse

            <a href="{{ route('pelatih.developments.index', $class) }}" class="text-gray-600">← Kembali</a>
        </div>
    </div>
</x-sidebar-layout>
