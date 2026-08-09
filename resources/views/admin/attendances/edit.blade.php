<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Koreksi Absensi</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="mb-2"><strong>Siswa:</strong> {{ $attendance->student->full_name }}</p>

                <form action="{{ route('admin.attendances.update', $attendance) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="attendance_date" value="Tanggal Pertemuan" />
                        <x-text-input id="attendance_date" type="date" name="attendance_date" class="mt-1 block w-full"
                                      value="{{ old('attendance_date', $attendance->attendance_date->format('Y-m-d')) }}" required />
                        <x-input-error :messages="$errors->get('attendance_date')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.attendances.history', $attendance->class_id) }}" class="px-4 py-2 bg-gray-200 rounded-md">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
