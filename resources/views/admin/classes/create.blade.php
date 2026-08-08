<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Kelas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="program_id" value="Program" />
                        <select id="program_id" name="program_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">-- Pilih Program --</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('program_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="coach_id" value="Coach" />
                        <select id="coach_id" name="coach_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">-- Pilih Coach --</option>
                            @foreach ($coaches as $coach)
                                <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('coach_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="name" value="Nama Kelas" />
                        <select id="name" name="name" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach (['Beginner', 'Advanced', 'Elite'] as $level)
                                <option value="{{ $level }}" {{ old('name') == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- <div>
                        <x-input-label for="capacity" value="Kapasitas (opsional)" />
                        <x-text-input id="capacity" type="number" name="capacity" class="mt-1 block w-full" value="{{ old('capacity') }}" />
                        <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                    </div> --}}

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.classes.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
