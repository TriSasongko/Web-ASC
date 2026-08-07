<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftarkan Anak</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('orangtua.registrations.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <h3 class="font-semibold text-gray-700">Data Anak</h3>

                    <div>
                        <x-input-label for="full_name" value="Nama Lengkap" />
                        <x-text-input id="full_name" name="full_name" class="mt-1 block w-full" value="{{ old('full_name') }}" required />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="birth_place" value="Tempat Lahir" />
                            <x-text-input id="birth_place" name="birth_place" class="mt-1 block w-full" value="{{ old('birth_place') }}" />
                        </div>
                        <div>
                            <x-input-label for="birth_date" value="Tanggal Lahir" />
                            <x-text-input id="birth_date" type="date" name="birth_date" class="mt-1 block w-full" value="{{ old('birth_date') }}" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="gender" value="Jenis Kelamin" />
                        <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="weight" value="Berat Badan (kg)" />
                            <x-text-input id="weight" type="number" step="0.1" name="weight" class="mt-1 block w-full" value="{{ old('weight') }}" />
                        </div>
                        <div>
                            <x-input-label for="height" value="Tinggi Badan (cm)" />
                            <x-text-input id="height" type="number" step="0.1" name="height" class="mt-1 block w-full" value="{{ old('height') }}" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" value="Alamat" />
                        <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address') }}</textarea>
                    </div>

                    <hr>

                    <h3 class="font-semibold text-gray-700">Pilih Program</h3>

                    <div>
                        <select id="program_id" name="program_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Program --</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }} — Rp{{ number_format($program->price, 0, ',', '.') }}
                                    ({{ $program->billing_type === 'per_bulan' ? 'per bulan' : $program->total_sessions.'x pertemuan' }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('program_id')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('orangtua.registrations.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Kirim Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
