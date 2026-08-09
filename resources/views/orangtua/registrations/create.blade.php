<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Daftarkan Anak</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Lengkapi data orang tua dan anak untuk mendaftarkan anak Anda.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-3xl">
            <form action="{{ route('orangtua.registrations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="border-b border-outline-variant/30 pb-6">
                    <h3 class="font-headline text-headline-sm text-on-surface mb-4">Data Orang Tua</h3>

                    <div>
                        <x-input-label for="phone" value="Nomor HP / WhatsApp" />
                        <x-text-input id="phone" name="phone" class="mt-1 block w-full"
                                      value="{{ old('phone', auth()->user()->phone) }}"
                                      placeholder="08xxxxxxxxxx" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        <p class="font-body-sm text-body-sm text-outline mt-2">Digunakan Admin untuk menghubungi Anda terkait pembayaran, jadwal, atau informasi penting lainnya.</p>
                    </div>
                </div>

                <div class="border-b border-outline-variant/30 pb-6">
                    <h3 class="font-headline text-headline-sm text-on-surface mb-4">Data Anak</h3>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="full_name" value="Nama Lengkap" />
                            <x-text-input id="full_name" name="full_name" class="mt-1 block w-full" value="{{ old('full_name') }}" required />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="birth_place" value="Tempat Lahir" />
                                <x-text-input id="birth_place" name="birth_place" class="mt-1 block w-full" value="{{ old('birth_place') }}" required />
                            </div>
                            <div>
                                <x-input-label for="birth_date" value="Tanggal Lahir" />
                                <x-text-input id="birth_date" type="date" name="birth_date" class="mt-1 block w-full" value="{{ old('birth_date') }}" required />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="gender" value="Jenis Kelamin" />
                            <select id="gender" name="gender" class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="weight" value="Berat Badan (kg)" />
                                <x-text-input id="weight" type="number" step="0.1" name="weight" class="mt-1 block w-full" value="{{ old('weight') }}" required />
                            </div>
                            <div>
                                <x-input-label for="height" value="Tinggi Badan (cm)" />
                                <x-text-input id="height" type="number" step="0.1" name="height" class="mt-1 block w-full" value="{{ old('height') }}" required />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="address" value="Alamat" />
                            <textarea id="address" name="address" class="mt-1 block w-full border-outline-variant rounded-lg px-3 py-2 bg-surface-container-lowest shadow-sm focus:border-primary focus:ring-primary/30" required>{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-headline text-headline-sm text-on-surface mb-4">Pilih Program</h3>

                    <div>
                        <select id="program_id" name="program_id" class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
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
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Kirim Pendaftaran</x-primary-button>
                    <a href="{{ route('orangtua.registrations.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
