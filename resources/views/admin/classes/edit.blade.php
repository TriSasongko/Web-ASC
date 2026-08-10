<x-sidebar-layout>
    @php
        $initialKompetitif = (bool) ($class->program?->is_kompetitif ?? $programs->firstWhere('id', $class->program_id)?->is_kompetitif ?? false);
    @endphp
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Edit Kelas</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Perbarui data kelas. Program selain Kompetitif hanya level Beginner; Kompetitif berisi Advance/Elite.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-3xl">
            <form action="{{ route('admin.classes.update', $class) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ kompetitif: @js($initialKompetitif) }">
                    <div>
                        <x-input-label for="program_id" value="Program" />
                        <select id="program_id" name="program_id" x-ref="programSelect"
                            @change="kompetitif = ($refs.programSelect.selectedOptions[0].dataset.kompetitif === '1')"
                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" data-kompetitif="{{ $program->is_kompetitif ? '1' : '0' }}" {{ old('program_id', $class->program_id) == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('program_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="name" value="Nama Kelas" />
                        <x-text-input id="name" name="name" value="{{ old('name', $class->name) }}" required />
                    </div>

                    <div>
                        <x-input-label for="level" value="Level Kelas" />
                        <select id="level" name="level" x-show="!kompetitif" :disabled="kompetitif"
                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                            <option value="1" {{ old('level', $class->level) == 1 ? 'selected' : '' }}>Beginner</option>
                        </select>
                        <select id="level" name="level" x-show="kompetitif" :disabled="!kompetitif"
                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                            <option value="2" {{ old('level', $class->level) == 2 ? 'selected' : '' }}>Advance (Kompetitif B)</option>
                            <option value="3" {{ old('level', $class->level) == 3 ? 'selected' : '' }}>Elite (Kompetitif A)</option>
                        </select>
                        <x-input-error :messages="$errors->get('level')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="capacity" value="Kapasitas" />
                        <x-text-input id="capacity" type="number" name="capacity" value="{{ old('capacity', $class->capacity) }}" />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ $class->is_active ? 'checked' : '' }} class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary/30">
                    <label for="is_active" class="font-body-md text-body-md text-on-surface">Kelas Aktif</label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Update</x-primary-button>
                    <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
