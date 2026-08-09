<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">E-Raport — {{ $student->full_name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex justify-end">
                <a href="{{ route('eraport.pdf', [$student, $development->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                    ⬇ Unduh PDF
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Identitas Siswa</h3>
                    <p>Nama: {{ $student->full_name }}</p>
                    <p>Coach: {{ $development->coach->name }}</p>
                    <p>Program: {{ $development->schoolClass->program->name }}</p>
                    <p>Periode: {{ $development->period }}</p>
                </div>

                <hr>

                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Kehadiran</h3>
                    <p>{{ $attendanceCount }} pertemuan hadir</p>
                </div>

                <hr>

                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Penilaian Perkembangan</h3>
                    <table class="w-full text-sm text-left">
                        <tbody>
                            <tr>
                                <td colspan="2" class="py-2 font-medium text-gray-600">Penilaian Umum</td>
                            </tr>
                            @foreach (\App\Models\Development::umumAspects() as $key => $label)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $label }}</td>
                                    <td class="px-4 py-2 font-semibold">{{ \App\Models\Development::scoreLabel($development->$key) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="py-2 pt-4 font-medium text-gray-600">Penilaian Aspek Khusus</td>
                            </tr>
                            @foreach (\App\Models\Development::khususAspects() as $key => $label)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $label }}</td>
                                    <td class="px-4 py-2 font-semibold">{{ \App\Models\Development::scoreLabel($development->$key) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($development->coach_note)
                    <hr>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Catatan Coach</h3>
                        <p>{{ $development->coach_note }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-sidebar-layout>
