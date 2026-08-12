@php
    $s = $settings;
@endphp

<div class="space-y-6">
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8">
        <div class="mb-6">
            <h3 class="font-headline text-headline-sm text-primary font-semibold">Seksi Tentang</h3>
            <p class="font-body-sm text-body-sm text-outline mt-1">Deskripsi singkat, visi, misi, dan gambar seksi Tentang pada halaman depan.</p>
        </div>

        <form action="{{ route('admin.settings.tentang') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="tentang_heading" value="Judul Seksi" />
                    <x-text-input id="tentang_heading" name="tentang_heading" class="mt-1 block w-full"
                        value="{{ old('tentang_heading', $s['tentang_heading'] ?? '') }}" required />
                    <x-input-error :messages="$errors->get('tentang_heading')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tentang_years" value="Angka Pengalaman" />
                    <x-text-input id="tentang_years" name="tentang_years" class="mt-1 block w-full"
                        value="{{ old('tentang_years', $s['tentang_years'] ?? '') }}" placeholder="10+" />
                    <x-input-error :messages="$errors->get('tentang_years')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="tentang_text" value="Deskripsi" />
                <textarea id="tentang_text" name="tentang_text" rows="4" required
                    class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('tentang_text', $s['tentang_text'] ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('tentang_text')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="tentang_visi" value="Visi" />
                    <textarea id="tentang_visi" name="tentang_visi" rows="3" required
                        class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('tentang_visi', $s['tentang_visi'] ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('tentang_visi')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tentang_misi" value="Misi (satu poin per baris)" />
                    <textarea id="tentang_misi" name="tentang_misi" rows="3"
                        class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('tentang_misi', $s['tentang_misi'] ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('tentang_misi')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="tentang_years_label" value="Label Pengalaman" />
                    <x-text-input id="tentang_years_label" name="tentang_years_label" class="mt-1 block w-full"
                        value="{{ old('tentang_years_label', $s['tentang_years_label'] ?? '') }}" placeholder="Tahun Pengalaman" />
                    <x-input-error :messages="$errors->get('tentang_years_label')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tentang_image" value="URL Gambar" />
                    <x-text-input id="tentang_image" name="tentang_image" class="mt-1 block w-full"
                        value="{{ old('tentang_image', $s['tentang_image'] ?? '') }}" placeholder="https://..." />
                    <x-input-error :messages="$errors->get('tentang_image')" class="mt-2" />
                    @if (! empty($s['tentang_image'] ?? ''))
                        <img src="{{ $s['tentang_image'] }}" alt="Pratinjau gambar tentang"
                            class="mt-3 w-40 h-24 object-cover rounded-lg border border-outline-variant/40">
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-primary-button>Simpan Perubahan Tentang</x-primary-button>
            </div>
        </form>
    </div>

    {{-- Daftar Coach --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden" x-data="{ showCoachForm: false }">
        <div class="flex items-center justify-between gap-4 p-6 border-b border-outline-variant/30">
            <div>
                <h3 class="font-headline text-headline-sm text-primary font-semibold">Tim Coach</h3>
                <p class="font-body-sm text-body-sm text-outline mt-1">Coach yang ditampilkan pada seksi Tentang dan halaman depan.</p>
            </div>
            <button type="button" @click="showCoachForm = ! showCoachForm"
                class="inline-flex items-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]" x-text="showCoachForm ? 'close' : 'add'">add</span>
                <span x-text="showCoachForm ? 'Batal' : 'Tambah Coach'">Tambah Coach</span>
            </button>
        </div>

        <div class="p-6">
            <form x-show="showCoachForm" x-cloak action="{{ route('admin.settings.coaches.store') }}" method="POST"
                class="mb-6 rounded-xl border border-primary/30 bg-primary/5 p-5 space-y-4">
                @csrf
                <h4 class="font-headline text-headline-sm text-primary font-semibold">Tambah Coach Baru</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="coach_name" value="Nama Coach" />
                        <x-text-input id="coach_name" name="name" class="mt-1 block w-full"
                            value="{{ old('name') }}" placeholder="Nama lengkap" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="coach_position" value="Jabatan" />
                        <x-text-input id="coach_position" name="position" class="mt-1 block w-full"
                            value="{{ old('position') }}" placeholder="Head Coach" required />
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>
                </div>
                <div>
                    <x-input-label for="coach_description" value="Deskripsi" />
                    <textarea id="coach_description" name="description" rows="3" required
                        class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="coach_photo_url" value="URL Foto" />
                        <x-text-input id="coach_photo_url" name="photo_url" class="mt-1 block w-full"
                            value="{{ old('photo_url') }}" placeholder="https://..." />
                        <x-input-error :messages="$errors->get('photo_url')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="coach_sort_order" value="Urutan" />
                        <x-text-input id="coach_sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full"
                            value="{{ old('sort_order', 0) }}" />
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>
                </div>
                <x-primary-button>Simpan Coach</x-primary-button>
            </form>

            <div class="space-y-4">
                @forelse ($coaches as $coach)
                    <div class="border border-outline-variant/30 rounded-xl overflow-hidden" x-data="{ editing: false }">
                        <div class="flex items-start gap-4 p-4">
                            @if ($coach->photo_url)
                                <img src="{{ $coach->photo_url }}" alt="{{ $coach->name }}" class="w-16 h-16 rounded-lg object-cover shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-on-surface-variant text-[28px]">person</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-headline text-headline-sm text-on-surface">{{ $coach->name }}</h4>
                                    <span class="px-2 py-0.5 rounded-full bg-tertiary-fixed text-primary font-label-sm text-label-sm">{{ $coach->position }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $coach->description }}</p>
                                <p class="font-label-sm text-label-sm text-outline mt-1">Urutan: {{ $coach->sort_order }}</p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" @click="editing = ! editing"
                                    class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]" x-text="editing ? 'close' : 'edit'">edit</span>
                                </button>
                                <form action="{{ route('admin.settings.coaches.destroy', $coach) }}" method="POST"
                                    onsubmit="return confirm('Hapus coach ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <form x-show="editing" x-cloak action="{{ route('admin.settings.coaches.update', $coach) }}" method="POST"
                            class="border-t border-outline-variant/30 bg-surface/50 p-5 space-y-4">
                            @csrf @method('PUT')
                            <h5 class="font-headline text-headline-sm text-primary font-semibold">Edit {{ $coach->name }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="coach_name_{{ $coach->id }}" value="Nama Coach" />
                                    <x-text-input id="coach_name_{{ $coach->id }}" name="name" class="mt-1 block w-full"
                                        value="{{ old('name', $coach->name) }}" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="coach_position_{{ $coach->id }}" value="Jabatan" />
                                    <x-text-input id="coach_position_{{ $coach->id }}" name="position" class="mt-1 block w-full"
                                        value="{{ old('position', $coach->position) }}" required />
                                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="coach_description_{{ $coach->id }}" value="Deskripsi" />
                                <textarea id="coach_description_{{ $coach->id }}" name="description" rows="3" required
                                    class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description', $coach->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="coach_photo_url_{{ $coach->id }}" value="URL Foto" />
                                    <x-text-input id="coach_photo_url_{{ $coach->id }}" name="photo_url" class="mt-1 block w-full"
                                        value="{{ old('photo_url', $coach->photo_url) }}" placeholder="https://..." />
                                    <x-input-error :messages="$errors->get('photo_url')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="coach_sort_order_{{ $coach->id }}" value="Urutan" />
                                    <x-text-input id="coach_sort_order_{{ $coach->id }}" name="sort_order" type="number" min="0" class="mt-1 block w-full"
                                        value="{{ old('sort_order', $coach->sort_order) }}" />
                                    <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-primary-button>Simpan Perubahan</x-primary-button>
                                <label class="inline-flex items-center gap-2 font-label-md text-label-md text-on-surface-variant">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="rounded border-outline-variant text-primary focus:ring-primary/40"
                                        {{ old('is_active', $coach->is_active) ? 'checked' : '' }}>
                                    Tampilkan di website
                                </label>
                            </div>
                        </form>
                    </div>
                @empty
                    <p class="text-center font-body-sm text-body-sm text-outline py-8">Belum ada coach. Tambahkan coach pertama Anda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
