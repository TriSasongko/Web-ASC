@php
    $s = $settings;
@endphp

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-4xl">
    <div class="mb-6">
        <h3 class="font-headline text-headline-sm text-primary font-semibold">Kontak Website</h3>
        <p class="font-body-sm text-body-sm text-outline mt-1">Nomor telepon dan alamat tersimpan pada data admin; sisanya dipakai di footer dan halaman Kontak.</p>
    </div>

    <form action="{{ route('admin.settings.kontak') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="phone" value="Nomor Telepon / WhatsApp" />
                <x-text-input id="phone" name="phone" class="mt-1 block w-full"
                    value="{{ old('phone', $adminPhone) }}" placeholder="081234567890" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="kontak_email" value="Email" />
                <x-text-input id="kontak_email" name="kontak_email" type="email" class="mt-1 block w-full"
                    value="{{ old('kontak_email', $s['kontak_email'] ?? '') }}" placeholder="email@example.com" required />
                <x-input-error :messages="$errors->get('kontak_email')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="address" value="Alamat" />
            <x-text-input id="address" name="address" class="mt-1 block w-full"
                value="{{ old('address', $adminAddress) }}"
                placeholder="Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, ..." required />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="kontak_instagram" value="URL Instagram" />
                <x-text-input id="kontak_instagram" name="kontak_instagram" class="mt-1 block w-full"
                    value="{{ old('kontak_instagram', $s['kontak_instagram'] ?? '') }}" placeholder="https://instagram.com/..." />
                <x-input-error :messages="$errors->get('kontak_instagram')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="kontak_instagram_handle" value="Username Instagram" />
                <x-text-input id="kontak_instagram_handle" name="kontak_instagram_handle" class="mt-1 block w-full"
                    value="{{ old('kontak_instagram_handle', $s['kontak_instagram_handle'] ?? '') }}" placeholder="@asc_lampung" />
                <x-input-error :messages="$errors->get('kontak_instagram_handle')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="kontak_maps_url" value="URL Google Maps (Embed)" />
            <x-text-input id="kontak_maps_url" name="kontak_maps_url" class="mt-1 block w-full"
                value="{{ old('kontak_maps_url', $s['kontak_maps_url'] ?? '') }}" placeholder="https://www.google.com/maps/embed?pb=..." />
            <x-input-error :messages="$errors->get('kontak_maps_url')" class="mt-2" />
            <p class="mt-2 font-body-sm text-body-sm text-outline">
                Gunakan tautan embed dari menu "Bagikan &gt; Embed peta" di Google Maps.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="kontak_hours_weekday" value="Jam Operasional (Senin–Jumat)" />
                <x-text-input id="kontak_hours_weekday" name="kontak_hours_weekday" class="mt-1 block w-full"
                    value="{{ old('kontak_hours_weekday', $s['kontak_hours_weekday'] ?? '') }}" placeholder="Senin – Jumat: 08.00 – 20.00" />
                <x-input-error :messages="$errors->get('kontak_hours_weekday')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="kontak_hours_weekend" value="Jam Operasional (Sabtu–Minggu)" />
                <x-text-input id="kontak_hours_weekend" name="kontak_hours_weekend" class="mt-1 block w-full"
                    value="{{ old('kontak_hours_weekend', $s['kontak_hours_weekend'] ?? '') }}" placeholder="Sabtu – Minggu: 07.00 – 18.00" />
                <x-input-error :messages="$errors->get('kontak_hours_weekend')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <x-primary-button>Simpan Pengaturan Kontak</x-primary-button>
        </div>
    </form>
</div>
