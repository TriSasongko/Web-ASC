<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Absensi — {{ $class->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.attendances.store', $class) }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="attendance_date" value="Tanggal Latihan" />
                            <x-text-input id="attendance_date" type="date" name="attendance_date" class="mt-1 block w-full"
                                          value="{{ old('attendance_date', now()->format('Y-m-d')) }}" required />
                            <x-input-error :messages="$errors->get('attendance_date')" class="mt-2" />
                        </div>
                        {{-- <div>
                            <x-input-label for="session_number" value="Sesi Ke-" />
                            <x-text-input id="session_number" type="number" min="1" name="session_number" class="mt-1 block w-full"
                                          value="{{ old('session_number', 1) }}" required />
                            <x-input-error :messages="$errors->get('session_number')" class="mt-2" />
                        </div> --}}
                    </div>

                    <hr>

                    <h3 class="font-semibold text-gray-700">Daftar Siswa</h3>
                    <p class="text-xs text-gray-500 mb-2">Centang siswa yang mengikuti latihan pada tanggal tersebut.</p>

                    @forelse ($students as $student)
                        <div class="flex items-center justify-between border-b py-2">
                            <div>
                                <p class="font-medium">{{ $student->full_name }}</p>
                                <p class="text-xs text-gray-500">
                                    Pertemuan terhitung: {{ $student->pivot->sessions_completed }}/{{ $class->program->total_sessions ?? '∞' }}
                                </p>
                            </div>
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="attendance[]" value="{{ $student->id }}" checked class="rounded border-gray-300">
                                Hadir
                            </label>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada siswa aktif di kelas ini.</p>
                    @endforelse

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('admin.attendances.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Simpan Absensi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
