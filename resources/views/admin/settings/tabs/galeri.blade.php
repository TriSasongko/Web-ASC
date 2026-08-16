@php
    $s = $settings;
    $aspects = ['4/3' => '4:3 (Horizontal)', '3/4' => '3:4 (Vertikal)', 'square' => 'Persegi', 'video' => 'Video (16:9)', '4/5' => '4:5 (Potret)'];
@endphp

<div class="space-y-6">
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8">
        <div class="mb-6">
            <h3 class="font-headline text-headline-sm text-primary font-semibold">Seksi Galeri</h3>
            <p class="font-body-sm text-body-sm text-outline mt-1">Judul dan deskripsi seksi galeri pada halaman depan.</p>
        </div>

        <form action="{{ route('admin.settings.galeri') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="galeri_heading" value="Judul Seksi" />
                <x-text-input id="galeri_heading" name="galeri_heading" class="mt-1 block w-full"
                    value="{{ old('galeri_heading', $s['galeri_heading'] ?? '') }}" required />
                <x-input-error :messages="$errors->get('galeri_heading')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="galeri_subtitle" value="Deskripsi Seksi" />
                <textarea id="galeri_subtitle" name="galeri_subtitle" rows="2"
                    class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('galeri_subtitle', $s['galeri_subtitle'] ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('galeri_subtitle')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-primary-button>Simpan Perubahan Galeri</x-primary-button>
            </div>
        </form>
    </div>

    {{-- Daftar Foto Galeri --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden" x-data="{ showGalleryForm: false }">
        <div class="flex items-center justify-between gap-4 p-6 border-b border-outline-variant/30">
            <div>
                <h3 class="font-headline text-headline-sm text-primary font-semibold">Foto Galeri</h3>
                <p class="font-body-sm text-body-sm text-outline mt-1">Foto yang ditampilkan pada seksi Galeri halaman depan dan halaman Galeri.</p>
            </div>
            <button type="button" @click="showGalleryForm = ! showGalleryForm"
                class="inline-flex items-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]" x-text="showGalleryForm ? 'close' : 'add'">add</span>
                <span x-text="showGalleryForm ? 'Batal' : 'Tambah Foto'">Tambah Foto</span>
            </button>
        </div>

        <div class="p-6">
            <form x-show="showGalleryForm" x-cloak x-data="{ aspect: 'square' }"
                action="{{ route('admin.settings.gallery.store') }}" method="POST"
                enctype="multipart/form-data"
                class="mb-6 rounded-xl border border-primary/30 bg-primary/5 p-5 space-y-4">
                @csrf
                <h4 class="font-headline text-headline-sm text-primary font-semibold">Tambah Foto Baru</h4>
                <div>
                    <x-input-label for="gallery_photo" value="Foto" />
                    <input type="file" id="gallery_photo" name="photo" accept="image/*" x-show="aspect !== 'video'"
                        class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    <x-input-label for="gallery_image_url" value="URL Video" />
                    <x-text-input id="gallery_image_url" name="image_url" class="mt-1 block w-full"
                        value="{{ old('image_url') }}" placeholder="https://..." x-show="aspect === 'video'" />
                    <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="gallery_title" value="Judul" />
                        <x-text-input id="gallery_title" name="title" class="mt-1 block w-full"
                            value="{{ old('title') }}" placeholder="Judul foto" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="gallery_category" value="Kategori" />
                        <select id="gallery_category" name="category"
                            class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="">Tanpa kategori</option>
                            @foreach (['Latihan', 'Kejuaraan', 'Keceriaan', 'Video'] as $cat)
                                <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>
                </div>
                <div>
                    <x-input-label for="gallery_description" value="Deskripsi" />
                    <x-text-input id="gallery_description" name="description" class="mt-1 block w-full"
                        value="{{ old('description') }}" placeholder="Keterangan singkat" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="gallery_aspect" value="Rasio Foto" />
                        <select id="gallery_aspect" name="aspect" required x-model="aspect"
                            class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            @foreach ($aspects as $value => $label)
                                <option value="{{ $value }}" @selected(old('aspect', 'square') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('aspect')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="gallery_sort_order" value="Urutan" />
                        <x-text-input id="gallery_sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full"
                            value="{{ old('sort_order', 0) }}" />
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>
                </div>
                <x-primary-button>Simpan Foto</x-primary-button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($gallery as $image)
                    <div class="border border-outline-variant/30 rounded-xl overflow-hidden" x-data="{ editing: false, deleteOpen: false, aspect: '{{ $image->aspect }}' }">
                        <div class="flex items-start gap-4 p-4">
                            <img src="{{ $image->url }}" alt="{{ $image->title ?? 'Foto galeri' }}"
                                class="w-20 h-20 rounded-lg object-cover shrink-0">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-headline text-headline-sm text-on-surface">{{ $image->title ?? 'Tanpa judul' }}</h4>
                                    @if ($image->category)
                                        <span class="px-2 py-0.5 rounded-full bg-tertiary-fixed text-primary font-label-sm text-label-sm">{{ $image->category }}</span>
                                    @endif
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 truncate">{{ $image->description }}</p>
                                <p class="font-label-sm text-label-sm text-outline mt-1">Urutan: {{ $image->sort_order }}</p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" @click="editing = ! editing"
                                    class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]" x-text="editing ? 'close' : 'edit'">edit</span>
                                </button>
                                <button type="button" @click="deleteOpen = true"
                                    class="p-2 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </div>

                        <form x-show="editing" x-cloak action="{{ route('admin.settings.gallery.update', $image) }}" method="POST"
                            enctype="multipart/form-data"
                            class="border-t border-outline-variant/30 bg-surface/50 p-5 space-y-4">
                            @csrf @method('PUT')
                            <h5 class="font-headline text-headline-sm text-primary font-semibold">Edit Foto</h5>
                            <div>
                                <x-input-label for="gallery_photo_{{ $image->id }}" value="Ganti Foto" />
                                @if ($image->aspect !== 'video' && $image->url)
                                    <img src="{{ $image->url }}" alt="{{ $image->title ?? 'Foto galeri' }}"
                                        class="mt-2 w-24 h-20 rounded-lg object-cover border border-outline-variant/40">
                                @endif
                                <input type="file" id="gallery_photo_{{ $image->id }}" name="photo" accept="image/*" x-show="aspect !== 'video'"
                                    class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                                <x-input-label for="gallery_image_url_{{ $image->id }}" value="URL Video" />
                                <x-text-input id="gallery_image_url_{{ $image->id }}" name="image_url" class="mt-1 block w-full"
                                    value="{{ old('image_url', $image->aspect === 'video' ? $image->image_url : '') }}" placeholder="https://..." x-show="aspect === 'video'" />
                                <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="gallery_title_{{ $image->id }}" value="Judul" />
                                    <x-text-input id="gallery_title_{{ $image->id }}" name="title" class="mt-1 block w-full"
                                        value="{{ old('title', $image->title) }}" />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="gallery_category_{{ $image->id }}" value="Kategori" />
                                    <select id="gallery_category_{{ $image->id }}" name="category"
                                        class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        <option value="">Tanpa kategori</option>
                                        @foreach (['Latihan', 'Kejuaraan', 'Keceriaan', 'Video'] as $cat)
                                            <option value="{{ $cat }}" @selected(old('category', $image->category) === $cat)>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="gallery_description_{{ $image->id }}" value="Deskripsi" />
                                <x-text-input id="gallery_description_{{ $image->id }}" name="description" class="mt-1 block w-full"
                                    value="{{ old('description', $image->description) }}" />
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="gallery_aspect_{{ $image->id }}" value="Rasio Foto" />
                                    <select id="gallery_aspect_{{ $image->id }}" name="aspect" required x-model="aspect"
                                        class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        @foreach ($aspects as $value => $label)
                                            <option value="{{ $value }}" @selected(old('aspect', $image->aspect) === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('aspect')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="gallery_sort_order_{{ $image->id }}" value="Urutan" />
                                    <x-text-input id="gallery_sort_order_{{ $image->id }}" name="sort_order" type="number" min="0" class="mt-1 block w-full"
                                        value="{{ old('sort_order', $image->sort_order) }}" />
                                    <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                                </div>
                                <div class="flex items-end">
                                    <label class="inline-flex items-center gap-2 font-label-md text-label-md text-on-surface-variant">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1" class="rounded border-outline-variant text-primary focus:ring-primary/40"
                                            @checked(old('is_active', $image->is_active))>
                                        Tampilkan
                                    </label>
                                </div>
                            </div>
                            <x-primary-button>Simpan Perubahan</x-primary-button>
                        </form>

                        <div x-show="deleteOpen" x-cloak x-transition.opacity
                            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
                            @click="deleteOpen = false"></div>

                        <div x-show="deleteOpen" x-cloak x-transition
                            @keydown.escape.window="deleteOpen = false"
                            class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="w-full max-w-sm bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden">
                                <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50">
                                    <div>
                                        <h3 class="font-headline text-headline-sm text-on-surface">Hapus Foto</h3>
                                        <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ $image->title ?? 'Tanpa judul' }}</p>
                                    </div>
                                    <button @click="deleteOpen = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">close</span>
                                    </button>
                                </div>
                                <div class="px-6 py-5">
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Yakin ingin menghapus foto ini? Tindakan ini tidak dapat dibatalkan.</p>
                                    <div class="flex items-center justify-end gap-2 mt-4">
                                        <button @click="deleteOpen = false" type="button" class="inline-flex items-center justify-center gap-2 border border-outline-variant/50 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                                            Batal
                                        </button>
                                        <form action="{{ route('admin.settings.gallery.destroy', $image) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 bg-error text-on-error px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center font-body-sm text-body-sm text-outline py-8 md:col-span-2">Belum ada foto galeri. Tambahkan foto pertama Anda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
