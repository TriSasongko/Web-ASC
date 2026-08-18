<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Edit Siswa</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Perbarui data siswa {{ $student->full_name }}.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-3xl">
            <div class="flex items-center gap-2 mb-5 pb-4 border-b border-outline-variant/30">
                <span class="material-symbols-outlined text-[#00687A]">family_restroom</span>
                <p class="font-body-sm text-body-sm text-outline">Orang Tua: {{ $student->parent->name }}</p>
            </div>

            <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="full_name" value="Nama Lengkap *" />
                        <x-text-input id="full_name" name="full_name" class="mt-1 block w-full" value="{{ old('full_name', $student->full_name) }}" required />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="nickname" value="Nama Panggilan" />
                        <x-text-input id="nickname" name="nickname" class="mt-1 block w-full" value="{{ old('nickname', $student->nickname) }}" />
                        <x-input-error :messages="$errors->get('nickname')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="birth_place" value="Tempat Lahir" />
                        <x-text-input id="birth_place" name="birth_place" class="mt-1 block w-full" value="{{ old('birth_place', $student->birth_place) }}" />
                        <x-input-error :messages="$errors->get('birth_place')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="birth_date" value="Tanggal Lahir" />
                        <x-text-input id="birth_date" type="date" name="birth_date" class="mt-1 block w-full" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}" />
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="gender" value="Jenis Kelamin *" />
                        <select id="gender" name="gender" class="mt-1 block w-full bg-surface-container-lowest border-outline-variant rounded-lg px-3 py-2 shadow-sm focus:border-primary focus:ring-primary/30" required>
                            <option value="L" {{ old('gender', $student->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $student->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="weight" value="Berat (kg)" />
                        <x-text-input id="weight" type="number" step="0.01" name="weight" class="mt-1 block w-full" value="{{ old('weight', $student->weight) }}" />
                        <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="height" value="Tinggi (cm)" />
                        <x-text-input id="height" type="number" step="0.01" name="height" class="mt-1 block w-full" value="{{ old('height', $student->height) }}" />
                        <x-input-error :messages="$errors->get('height')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="address" value="Alamat" />
                        <textarea id="address" name="address" class="mt-1 block w-full bg-surface-container-lowest border-outline-variant rounded-lg px-3 py-2 shadow-sm focus:border-primary focus:ring-primary/30">{{ old('address', $student->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Update</x-primary-button>
                    <a href="{{ route('admin.students.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
