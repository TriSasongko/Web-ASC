@php
    $s = $settings;
@endphp

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8">
    <div class="mb-6">
        <h3 class="font-headline text-headline-sm text-primary font-semibold">Seksi Hero</h3>
        <p class="font-body-sm text-body-sm text-outline mt-1">Judul, deskripsi, dan gambar bagian paling atas halaman depan.</p>
    </div>

    <form action="{{ route('admin.settings.hero') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="hero_title" value="Judul Hero" />
                <x-text-input id="hero_title" name="hero_title" class="mt-1 block w-full"
                    value="{{ old('hero_title', $s['hero_title'] ?? '') }}" placeholder="Belajar Renang Bersama " required />
                <x-input-error :messages="$errors->get('hero_title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="hero_highlight" value="Kata yang Disorot (oranye)" />
                <x-text-input id="hero_highlight" name="hero_highlight" class="mt-1 block w-full"
                    value="{{ old('hero_highlight', $s['hero_highlight'] ?? '') }}" placeholder="Coach Berpengalaman" />
                <x-input-error :messages="$errors->get('hero_highlight')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="hero_subtitle" value="Deskripsi Hero" />
            <textarea id="hero_subtitle" name="hero_subtitle" rows="3" required
                class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('hero_subtitle', $s['hero_subtitle'] ?? '') }}</textarea>
            <x-input-error :messages="$errors->get('hero_subtitle')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="hero_image" value="URL Gambar Latar (Background)" />
                <x-text-input id="hero_image" name="hero_image" class="mt-1 block w-full"
                    value="{{ old('hero_image', $s['hero_image'] ?? '') }}" placeholder="https://..." />
                <x-input-error :messages="$errors->get('hero_image')" class="mt-2" />
                @if (! empty($s['hero_image'] ?? ''))
                    <img src="{{ $s['hero_image'] }}" alt="Pratinjau latar hero"
                        class="mt-3 w-40 h-24 object-cover rounded-lg border border-outline-variant/40">
                @endif
            </div>

            <div>
                <x-input-label for="hero_side_image" value="URL Foto Samping" />
                <x-text-input id="hero_side_image" name="hero_side_image" class="mt-1 block w-full"
                    value="{{ old('hero_side_image', $s['hero_side_image'] ?? '') }}" placeholder="https://..." />
                <x-input-error :messages="$errors->get('hero_side_image')" class="mt-2" />
                @if (! empty($s['hero_side_image'] ?? ''))
                    <img src="{{ $s['hero_side_image'] }}" alt="Pratinjau foto samping"
                        class="mt-3 w-40 h-24 object-cover rounded-lg border border-outline-variant/40">
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-input-label for="hero_side_image_alt" value="Alt Text Foto Samping" />
                <x-text-input id="hero_side_image_alt" name="hero_side_image_alt" class="mt-1 block w-full"
                    value="{{ old('hero_side_image_alt', $s['hero_side_image_alt'] ?? '') }}" placeholder="Deskripsi singkat foto" />
                <x-input-error :messages="$errors->get('hero_side_image_alt')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="hero_cta_primary" value="Tombol Utama" />
                <x-text-input id="hero_cta_primary" name="hero_cta_primary" class="mt-1 block w-full"
                    value="{{ old('hero_cta_primary', $s['hero_cta_primary'] ?? '') }}" placeholder="Daftar Sekarang" />
                <x-input-error :messages="$errors->get('hero_cta_primary')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="hero_cta_secondary" value="Tombol Sekunder" />
                <x-text-input id="hero_cta_secondary" name="hero_cta_secondary" class="mt-1 block w-full"
                    value="{{ old('hero_cta_secondary', $s['hero_cta_secondary'] ?? '') }}" placeholder="Lihat Program" />
                <x-input-error :messages="$errors->get('hero_cta_secondary')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <x-primary-button>Simpan Perubahan Hero</x-primary-button>
        </div>
    </form>
</div>
