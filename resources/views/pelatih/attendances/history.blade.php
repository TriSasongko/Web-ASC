<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Absensi — {{ $class->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Sesi</th>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $a)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $a->attendance_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-2">Sesi {{ $a->session_number }}</td>
                                <td class="px-4 py-2">{{ $a->student->full_name }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs {{ $a->status === 'hadir' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $a->status === 'hadir' ? 'Hadir' : 'Tidak Hadir' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada riwayat absensi.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $attendances->links() }}</div>
            </div>

            <a href="{{ route('pelatih.attendances.index') }}" class="text-gray-600 mt-4 inline-block">← Kembali</a>
        </div>
    </div>
</x-app-layout>
