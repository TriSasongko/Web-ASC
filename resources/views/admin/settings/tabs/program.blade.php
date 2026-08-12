@php
    $s = $settings;
@endphp

<div class="space-y-6">
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8">
        <div class="mb-6">
            <h3 class="font-headline text-headline-sm text-primary font-semibold">Seksi Program</h3>
            <p class="font-body-sm text-body-sm text-outline mt-1">Judul dan deskripsi seksi program pada halaman depan.</p>
        </div>

        <form action="{{ route('admin.settings.program') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="program_heading" value="Judul Seksi" />
                <x-text-input id="program_heading" name="program_heading" class="mt-1 block w-full"
                    value="{{ old('program_heading', $s['program_heading'] ?? '') }}" required />
                <x-input-error :messages="$errors->get('program_heading')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="program_subtitle" value="Deskripsi Seksi" />
                <textarea id="program_subtitle" name="program_subtitle" rows="2"
                    class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('program_subtitle', $s['program_subtitle'] ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('program_subtitle')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-primary-button>Simpan Perubahan Program</x-primary-button>
            </div>
        </form>
    </div>

    {{-- Daftar Program --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden" x-data="{ showProgramForm: false }">
        <div class="flex items-center justify-between gap-4 p-6 border-b border-outline-variant/30">
            <div>
                <h3 class="font-headline text-headline-sm text-primary font-semibold">Kartu Program</h3>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kartu program yang ditampilkan pada seksi Program halaman depan.</p>
            </div>
            <button type="button" @click="showProgramForm = ! showProgramForm"
                class="inline-flex items-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]" x-text="showProgramForm ? 'close' : 'add'">add</span>
                <span x-text="showProgramForm ? 'Batal' : 'Tambah Program'">Tambah Program</span>
            </button>
        </div>

        <div class="p-6">
            <form x-show="showProgramForm" x-cloak action="{{ route('admin.settings.programs.store') }}" method="POST"
                class="mb-6 rounded-xl border border-primary/30 bg-primary/5 p-5 space-y-4">
                @csrf
                <h4 class="font-headline text-headline-sm text-primary font-semibold">Tambah Program Baru</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="program_name" value="Nama Program" />
                        <x-text-input id="program_name" name="name" class="mt-1 block w-full"
                            value="{{ old('name') }}" placeholder="Private" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="program_subtitle_item" value="Subjudul (rasio kelas)" />
                        <x-text-input id="program_subtitle_item" name="subtitle" class="mt-1 block w-full"
                            value="{{ old('subtitle') }}" placeholder="1 Coach : 1 Siswa" />
                        <x-input-error :messages="$errors->get('subtitle')" class="mt-2" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="program_price" value="Harga (Rp)" />
                        <x-text-input id="program_price" name="price" type="number" min="0" step="1000" class="mt-1 block w-full"
                            value="{{ old('price') }}" placeholder="500000" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="program_billing_unit" value="Satuan Harga" />
                        <select id="program_billing_unit" name="billing_unit" required
                            class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            @foreach (['/sesi', '/bulan', '/paket'] as $unit)
                                <option value="{{ $unit }}" @selected(old('billing_unit') === $unit)>{{ $unit }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('billing_unit')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="program_badge" value="Badge (opsional)" />
                        <x-text-input id="program_badge" name="badge" class="mt-1 block w-full"
                            value="{{ old('badge') }}" placeholder="POPULER" />
                        <x-input-error :messages="$errors->get('badge')" class="mt-2" />
                    </div>
                </div>
                <div>
                    <x-input-label for="program_features" value="Keunggulan (satu per baris)" />
                    <textarea id="program_features" name="features" rows="4"
                        class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('features') }}</textarea>
                    <x-input-error :messages="$errors->get('features')" class="mt-2" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="program_button_label" value="Label Tombol" />
                        <x-text-input id="program_button_label" name="button_label" class="mt-1 block w-full"
                            value="{{ old('button_label') }}" placeholder="Pilih Program" />
                        <x-input-error :messages="$errors->get('button_label')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="program_sort_order" value="Urutan" />
                        <x-text-input id="program_sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full"
                            value="{{ old('sort_order', 0) }}" />
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>
                    <div class="flex items-end gap-4">
                        <label class="inline-flex items-center gap-2 font-label-md text-label-md text-on-surface-variant">
                            <input type="hidden" name="featured" value="0">
                            <input type="checkbox" name="featured" value="1" class="rounded border-outline-variant text-primary focus:ring-primary/40"
                                @checked(old('featured'))>
                            Unggulan
                        </label>
                        <label class="inline-flex items-center gap-2 font-label-md text-label-md text-on-surface-variant">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-outline-variant text-primary focus:ring-primary/40" checked>
                            Aktif
                        </label>
                    </div>
                </div>
                <x-primary-button>Simpan Program</x-primary-button>
            </form>

            <div class="space-y-4">
                @forelse ($programs as $program)
                    <div class="border border-outline-variant/30 rounded-xl overflow-hidden" x-data="{ editing: false }">
                        <div class="flex items-start gap-4 p-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-headline text-headline-sm text-on-surface">{{ $program->name }}</h4>
                                    @if ($program->badge)
                                        <span class="px-2 py-0.5 rounded-full bg-orange text-white font-label-sm text-label-sm">{{ $program->badge }}</span>
                                    @endif
                                    @if ($program->featured)
                                        <span class="px-2 py-0.5 rounded-full bg-primary text-on-primary font-label-sm text-label-sm">Unggulan</span>
                                    @endif
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $program->subtitle }}</p>
                                <p class="font-headline text-headline-sm text-orange mt-1">
                                    @if ($program->price)
                                        Rp{{ number_format($program->price, 0, ',', '.') }}<span class="font-body-sm text-body-sm text-on-surface-variant">{{ $program->billing_unit }}</span>
                                    @else
                                        <span class="font-body-sm text-body-sm text-outline">Hubungi kami</span>
                                    @endif
                                </p>
                                <ul class="mt-2 space-y-1">
                                    @foreach ($program->featureList() as $feature)
                                        <li class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface-variant">
                                            <span class="material-symbols-outlined text-primary text-[16px]">check_circle</span>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                                <p class="font-label-sm text-label-sm text-outline mt-1">Urutan: {{ $program->sort_order }}</p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" @click="editing = ! editing"
                                    class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]" x-text="editing ? 'close' : 'edit'">edit</span>
                                </button>
                                <form action="{{ route('admin.settings.programs.destroy', $program) }}" method="POST"
                                    onsubmit="return confirm('Hapus program ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <form x-show="editing" x-cloak action="{{ route('admin.settings.programs.update', $program) }}" method="POST"
                            class="border-t border-outline-variant/30 bg-surface/50 p-5 space-y-4">
                            @csrf @method('PUT')
                            <h5 class="font-headline text-headline-sm text-primary font-semibold">Edit {{ $program->name }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="program_name_{{ $program->id }}" value="Nama Program" />
                                    <x-text-input id="program_name_{{ $program->id }}" name="name" class="mt-1 block w-full"
                                        value="{{ old('name', $program->name) }}" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="program_subtitle_item_{{ $program->id }}" value="Subjudul" />
                                    <x-text-input id="program_subtitle_item_{{ $program->id }}" name="subtitle" class="mt-1 block w-full"
                                        value="{{ old('subtitle', $program->subtitle) }}" />
                                    <x-input-error :messages="$errors->get('subtitle')" class="mt-2" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="program_price_{{ $program->id }}" value="Harga (Rp)" />
                                    <x-text-input id="program_price_{{ $program->id }}" name="price" type="number" min="0" step="1000" class="mt-1 block w-full"
                                        value="{{ old('price', $program->price) }}" />
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="program_billing_unit_{{ $program->id }}" value="Satuan Harga" />
                                    <select id="program_billing_unit_{{ $program->id }}" name="billing_unit" required
                                        class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        @foreach (['/sesi', '/bulan', '/paket'] as $unit)
                                            <option value="{{ $unit }}" @selected(old('billing_unit', $program->billing_unit) === $unit)>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('billing_unit')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="program_badge_{{ $program->id }}" value="Badge (opsional)" />
                                    <x-text-input id="program_badge_{{ $program->id }}" name="badge" class="mt-1 block w-full"
                                        value="{{ old('badge', $program->badge) }}" />
                                    <x-input-error :messages="$errors->get('badge')" class="mt-2" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="program_features_{{ $program->id }}" value="Keunggulan (satu per baris)" />
                                <textarea id="program_features_{{ $program->id }}" name="features" rows="4"
                                    class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('features', $program->features) }}</textarea>
                                <x-input-error :messages="$errors->get('features')" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="program_button_label_{{ $program->id }}" value="Label Tombol" />
                                    <x-text-input id="program_button_label_{{ $program->id }}" name="button_label" class="mt-1 block w-full"
                                        value="{{ old('button_label', $program->button_label) }}" />
                                    <x-input-error :messages="$errors->get('button_label')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="program_sort_order_{{ $program->id }}" value="Urutan" />
                                    <x-text-input id="program_sort_order_{{ $program->id }}" name="sort_order" type="number" min="0" class="mt-1 block w-full"
                                        value="{{ old('sort_order', $program->sort_order) }}" />
                                    <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                                </div>
                                <div class="flex items-end gap-4">
                                    <label class="inline-flex items-center gap-2 font-label-md text-label-md text-on-surface-variant">
                                        <input type="hidden" name="featured" value="0">
                                        <input type="checkbox" name="featured" value="1" class="rounded border-outline-variant text-primary focus:ring-primary/40"
                                            @checked(old('featured', $program->featured))>
                                        Unggulan
                                    </label>
                                    <label class="inline-flex items-center gap-2 font-label-md text-label-md text-on-surface-variant">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1" class="rounded border-outline-variant text-primary focus:ring-primary/40"
                                            @checked(old('is_active', $program->is_active))>
                                        Aktif
                                    </label>
                                </div>
                            </div>
                            <x-primary-button>Simpan Perubahan</x-primary-button>
                        </form>
                    </div>
                @empty
                    <p class="text-center font-body-sm text-body-sm text-outline py-8">Belum ada program. Tambahkan program pertama Anda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
