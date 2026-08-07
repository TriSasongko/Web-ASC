<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pendaftaran Anak Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-end mb-4">
                    <a href="{{ route('orangtua.registrations.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                        + Daftarkan Anak
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama Anak</th>
                            <th class="px-4 py-2">Program</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $reg)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $reg->student->full_name }}</td>
                                <td class="px-4 py-2">{{ $reg->program->name }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $badge = match($reg->status) {
                                            'diterima' => 'bg-green-100 text-green-700',
                                            'ditolak' => 'bg-red-100 text-red-700',
                                            default => 'bg-yellow-100 text-yellow-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                                        {{ str_replace('_', ' ', ucfirst($reg->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $reg->rejection_reason ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada pendaftaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $registrations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
