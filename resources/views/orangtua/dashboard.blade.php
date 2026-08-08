<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Orang Tua</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                Selamat datang, {{ auth()->user()->name }}!
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
