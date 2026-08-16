@php
    $s = $settings;
    $existingRows = old('rows') ?? $jadwalRows;
@endphp

<div class="space-y-6">
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8" x-data="jadwalSetting()">
        <div class="mb-6">
            <h3 class="font-headline text-headline-sm text-primary font-semibold">Jadwal Latihan Reguler</h3>
            <p class="font-body-sm text-body-sm text-outline mt-1">Atur judul, deskripsi, dan baris jadwal yang tampil pada seksi Jadwal Latihan halaman depan.</p>
        </div>

        <form action="{{ route('admin.settings.jadwal') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="jadwal_heading" value="Judul Seksi" />
                <x-text-input id="jadwal_heading" name="jadwal_heading" class="mt-1 block w-full"
                    value="{{ old('jadwal_heading', $s['jadwal_heading'] ?? 'Jadwal Latihan Reguler') }}" required />
                <x-input-error :messages="$errors->get('jadwal_heading')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="jadwal_subtitle" value="Deskripsi Seksi" />
                <textarea id="jadwal_subtitle" name="jadwal_subtitle" rows="2"
                    class="mt-1 block w-full rounded-lg border border-outline-variant bg-background px-4 py-3 text-body-md text-on-background focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('jadwal_subtitle', $s['jadwal_subtitle'] ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('jadwal_subtitle')" class="mt-2" />
            </div>

            <div>
                <div class="flex items-center justify-between gap-3 mb-3">
                    <div>
                        <x-input-label value="Baris Jadwal" />
                        <p class="font-body-sm text-body-sm text-outline mt-0.5">Tambah, ubah, atau hapus baris jadwal.</p>
                    </div>
                    <button type="button" @click="addRow()"
                        class="inline-flex items-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 shrink-0">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Baris
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-outline-variant/30">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Hari</th>
                                <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Jam</th>
                                <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Program</th>
                                <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lokasi</th>
                                <th class="px-4 py-3 w-20"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            <template x-for="(row, index) in rows" :key="index">
                                <tr>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="row.day" :name="'rows['+index+'][day]'" required
                                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"
                                            placeholder="Senin & Rabu">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="row.time" :name="'rows['+index+'][time]'" required
                                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"
                                            placeholder="15:30 - 17:00">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="row.program" :name="'rows['+index+'][program]'" required
                                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"
                                            placeholder="Reguler Pemula & Lanjutan">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="row.location" :name="'rows['+index+'][location]'" required
                                            class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"
                                            placeholder="Kolam Renang Universitas Lampung">
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="editRow(index)"
                                                class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit baris">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </button>
                                            <button type="button" @click="confirmDeleteRow(index)"
                                                class="p-2 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus baris">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <x-input-error :messages="$errors->get('rows')" class="mt-2" />
                <x-input-error :messages="$errors->get('rows.0.day')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-primary-button>Simpan Perubahan Jadwal</x-primary-button>
            </div>
        </form>

        <div x-show="pendingEdit !== null" x-cloak x-transition.opacity
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
            @click="pendingEdit = null"></div>

        <div x-show="pendingEdit !== null" x-cloak x-transition
            @keydown.escape.window="pendingEdit = null"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden">
                <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50">
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">Edit Baris Jadwal</h3>
                        <p class="font-body-sm text-body-sm text-outline mt-0.5" x-text="pendingEdit !== null ? (editDraft.day || '') + ' — ' + (editDraft.time || '') : ''"></p>
                    </div>
                    <button @click="pendingEdit = null" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <x-input-label value="Hari" />
                        <input type="text" x-model="editDraft.day"
                            class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"
                            placeholder="Senin & Rabu">
                    </div>
                    <div>
                        <x-input-label value="Jam" />
                        <input type="text" x-model="editDraft.time"
                            class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"
                            placeholder="15:30 - 17:00">
                    </div>
                    <div>
                        <x-input-label value="Program" />
                        <input type="text" x-model="editDraft.program"
                            class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"
                            placeholder="Reguler Pemula & Lanjutan">
                    </div>
                    <div>
                        <x-input-label value="Lokasi" />
                        <input type="text" x-model="editDraft.location"
                            class="mt-1 block w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"
                            placeholder="Kolam Renang Universitas Lampung">
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button @click="pendingEdit = null" type="button" class="inline-flex items-center justify-center gap-2 border border-outline-variant/50 text-on-surface-variant px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all">
                            Batal
                        </button>
                        <button @click="saveEdit()" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function jadwalSetting() {
            return {
                rows: @json($existingRows),
                pendingEdit: null,
                editDraft: { day: '', time: '', program: '', location: '' },
                addRow() {
                    this.rows.push({ day: '', time: '', program: '', location: '' });
                },
                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows = this.rows.filter((_, i) => i !== index);
                    }
                },
                confirmDeleteRow(index) {
                    const row = this.rows[index];
                    const label = (row?.day || '') + ' — ' + (row?.time || '');
                    window.Swal.fire({
                        title: 'Hapus Baris Jadwal?',
                        html: 'Baris <strong>' + label + '</strong> akan dihapus.',
                        text: 'Tindakan ini tidak dapat dibatalkan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#D32F2F',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.removeRow(index);
                        }
                    });
                },
                editRow(index) {
                    this.editDraft = { ...this.rows[index] };
                    this.pendingEdit = index;
                },
                saveEdit() {
                    if (this.pendingEdit !== null) {
                        this.rows = this.rows.map((row, i) => i === this.pendingEdit ? { ...this.editDraft } : row);
                    }
                    this.pendingEdit = null;
                }
            };
        }
    </script>
</div>
