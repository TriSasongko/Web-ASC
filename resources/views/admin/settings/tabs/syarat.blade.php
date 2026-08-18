@php
    $s = $settings;
@endphp

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-4xl">
    <div class="mb-6">
        <h3 class="font-headline text-headline-sm text-primary font-semibold">Syarat &amp; Ketentuan</h3>
        <p class="font-body-sm text-body-sm text-outline mt-1">Konten yang ditampilkan pada modal pendaftaran siswa baru.</p>
    </div>

    <form action="{{ route('admin.settings.syarat') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="syarat_ketentuan" value="Isi Syarat & Ketentuan" />
            <p class="font-body-xs text-body-xs text-outline mt-1 mb-2">
                Tulis teks biasa. Format otomatis:<br>
                &bull; Judul bagian diawali huruf besar + titik, contoh: <code>A. Ketentuan Umum</code><br>
                &bull; Daftar bernomor diawali angka + titik, contoh: <code>1. Isi poin pertama</code><br>
                &bull; Kosongkan satu baris untuk pemisah antar bagian.
            </p>
            <textarea id="syarat_ketentuan" name="syarat_ketentuan" rows="25"
                class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-sm text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('syarat_ketentuan', $s['syarat_ketentuan'] ?? '') }}</textarea>
            <x-input-error :messages="$errors->get('syarat_ketentuan')" class="mt-2" />
        </div>

        <div class="flex items-center gap-3">
            <x-primary-button>Simpan Perubahan</x-primary-button>
        </div>
    </form>
</div>
