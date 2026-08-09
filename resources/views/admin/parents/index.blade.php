<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Manajemen Orang Tua</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola data orang tua siswa ASC Academy.</p>
            </div>
            <a href="{{ route('admin.parents.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Orang Tua
            </a>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <form method="GET" class="flex items-center gap-2">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama orang tua..." class="w-full sm:w-64 pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant/50 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary font-body-sm text-body-sm transition-all">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2 rounded-full font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">No. HP</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($parents as $parent)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $parent->name }}</td>
                                <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $parent->email }}</td>
                                <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $parent->phone ?? '-' }}</td>
                                <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">
                                    @if ($parent->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.parents.show', $parent) }}" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Detail">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.parents.edit', $parent) }}" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.parents.toggle-active', $parent) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="{{ $parent->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <span class="material-symbols-outlined text-[20px]">{{ $parent->is_active ? 'person_off' : 'person_add' }}</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.parents.reset-password', $parent) }}" method="POST" class="inline" onsubmit="return confirm('Reset password ke default?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Reset Password">
                                                <span class="material-symbols-outlined text-[20px]">key</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.parents.destroy', $parent) }}" method="POST" class="inline" onsubmit="return confirm('Hapus orang tua ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada data orang tua.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <label for="per_page" class="font-body-sm text-body-sm text-on-surface-variant whitespace-nowrap">Tampilkan</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-1.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                        @foreach ([5, 10, 25, 50] as $p)
                            <option value="{{ $p }}" {{ request('per_page', 10) == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                    <span class="font-body-sm text-body-sm text-on-surface-variant whitespace-nowrap">per halaman</span>
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>
                <div class="w-full">{{ $parents->links('vendor.pagination.prevnext') }}</div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
