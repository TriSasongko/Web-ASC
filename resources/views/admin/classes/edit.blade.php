<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kelas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.classes.update', $class) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="program_id" value="Program" />
                        <select id="program_id" name="program_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" {{ old('program_id', $class->program_id) == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="coach_id" value="Coach" />
                        <select id="coach_id" name="coach_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @foreach ($coaches as $coach)
                                <option value="{{ $coach->id }}" {{ old('coach_id', $class->coach_id) == $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="name" value="Nama Kelas" />
                        <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $class->name) }}" required />
                    </div>

                    <div>
                        <x-input-label for="level" value="Tingkatan (angka, opsional)" />
                        <x-text-input id="level" type="number" min="1" name="level" class="mt-1 block w-full" value="{{ old('level', $class->level) }}" />
                    </div>

                    <div>
                        <x-input-label for="capacity" value="Kapasitas" />
                        <x-text-input id="capacity" type="number" name="capacity" class="mt-1 block w-full" value="{{ old('capacity', $class->capacity) }}" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ $class->is_active ? 'checked' : '' }}>
                        <label for="is_active">Kelas Aktif</label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.classes.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
