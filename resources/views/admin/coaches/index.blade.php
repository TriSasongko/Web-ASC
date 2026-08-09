<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Manajemen Pelatih</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola data pelatih ASC Academy.</p>
            </div>
            <a href="{{ route('admin.coaches.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Pelatih
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
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelatih..." class="w-full sm:w-64 pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant/50 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary font-body-sm text-body-sm transition-all">
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
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Foto</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">No. HP</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($coaches as $coach)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">
                                    @if ($coach->photo)
                                        <img src="{{ Storage::url($coach->photo) }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-surface-container text-on-surface-variant flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[20px]">person</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $coach->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $coach->email }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $coach->phone ?? '-' }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm {{ $coach->is_active ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-error-container text-on-error-container' }}">
                                        {{ $coach->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.coaches.edit', $coach) }}" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.coaches.toggle-active', $coach) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="{{ $coach->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <span class="material-symbols-outlined text-[20px]">{{ $coach->is_active ? 'person_off' : 'person_add' }}</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.coaches.reset-password', $coach) }}" method="POST" class="inline" onsubmit="return confirm('Reset password ke default?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Reset Password">
                                                <span class="material-symbols-outlined text-[20px]">key</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.coaches.destroy', $coach) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pelatih ini?')">
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
                                <td colspan="6" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada data pelatih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-outline-variant/30">{{ $coaches->links() }}</div>
        </div>
    </div>
</x-sidebar-layout>
