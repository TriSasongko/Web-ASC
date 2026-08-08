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
                        <div class="flex items-center gap-3">
                            <a href="{{ route('eraport.show', [$student, $dev->id]) }}" class="text-indigo-600 text-sm">Lihat E-Raport →</a>
                            <form action="{{ route('admin.developments.destroy', $dev) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        @foreach (\App\Models\Development::aspects() as $key => $label)
                            <p>{{ $label }}: <strong>{{ str_replace('_', ' ', ucfirst($dev->$key)) }}</strong></p>
                        @endforeach
                    </div>
                    @if ($dev->coach_note)
                        <p class="text-sm text-gray-600 mt-2">Catatan: {{ $dev->coach_note }}</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">Belum ada penilaian untuk siswa ini.</p>
            @endforelse

            <a href="{{ route('admin.classes.developments.index', $class) }}" class="text-gray-600">← Kembali</a>
        </div>
    </div>
</x-sidebar-layout>
