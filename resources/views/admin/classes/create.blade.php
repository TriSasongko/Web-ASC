<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Tambah Kelas</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Buat kelas baru untuk ASC Academy.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-3xl">
            <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="program_id" value="Program" />
                        <select id="program_id" name="program_id" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                            <option value="">-- Pilih Program --</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('program_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="coach_id" value="Coach" />
                        <select id="coach_id" name="coach_id" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                            <option value="">-- Pilih Coach --</option>
                            @foreach ($coaches as $coach)
                                <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('coach_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="name" value="Nama Kelas" />
                        <x-text-input id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Reguler A" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="level" value="Level Kelas" />
                        <select id="level" name="level" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach (\App\Models\SchoolClass::levelOptions() as $levelValue => $levelLabel)
                                <option value="{{ $levelValue }}" {{ old('level') == $levelValue ? 'selected' : '' }}>{{ $levelLabel }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('level')" class="mt-2" />
                    </div>
                </div>

                {{-- <div>
                    <x-input-label for="capacity" value="Kapasitas (opsional)" />
                    <x-text-input id="capacity" type="number" name="capacity" class="mt-1 block w-full" value="{{ old('capacity') }}" />
                    <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                </div> --}}

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>
                    <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
