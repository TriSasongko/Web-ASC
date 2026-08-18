<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Data Siswa</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola dan pantau data siswa beserta paketnya.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center gap-1 bg-surface-container-low rounded-xl p-1 w-fit max-w-full overflow-x-auto">
            @foreach ([null => 'Semua'] + \App\Models\SchoolClass::levelOptions() as $lv => $label)
                <a href="{{ route('admin.students.index', array_filter([
                    'status' => $status,
                    'level' => $lv,
                    'search' => request('search'),
                    'per_page' => request('per_page'),
                ])) }}"
                   class="px-4 py-2 rounded-lg font-label-md text-label-md whitespace-nowrap transition-all {{ ($level ?? null) === $lv ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-lowest' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <form method="GET" class="flex flex-wrap items-center gap-2">
                    @if ($level !== null)
                        <input type="hidden" name="level" value="{{ $level }}">
                    @endif
                    <select name="status" onchange="this.form.submit()"
                            class="w-full sm:w-auto bg-surface-container-low border border-outline-variant/50 rounded-full px-4 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="perlu_konfirmasi" {{ $status === 'perlu_konfirmasi' ? 'selected' : '' }}>Perlu Konfirmasi</option>
                        <option value="berhenti" {{ $status === 'berhenti' ? 'selected' : '' }}>Berhenti</option>
                        <option value="pindah" {{ $status === 'pindah' ? 'selected' : '' }}>Pindah</option>
                    </select>
                    <div class="relative flex-1 min-w-0">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant/50 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary font-body-sm text-body-sm transition-all">
                    </div>
                    <button type="submit" class="shrink-0 inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2 rounded-full font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <span class="sm:hidden">Cari</span>
                    </button>
                </form>
            </div>

            {{-- Mobile: kartu siswa --}}
            <div class="md:hidden divide-y divide-outline-variant/30">
                @forelse ($students as $student)
                    @php
                        $pivots = $student->classes->filter(fn ($c) => $c->pivot->is_active);
                        if ($pivots->isEmpty() && in_array($status, ['berhenti', 'pindah'], true)) {
                            $pivots = $student->classes->filter(fn ($c) => $c->pivot->renewal_status === $status);
                        }
                    @endphp
                    <div class="p-4 hover:bg-surface-container-low/50 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-primary-container/60 text-on-primary flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">child_care</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $student->full_name }}</p>
                                    <p class="font-body-sm text-body-sm text-outline truncate">{{ $student->parent->name }}</p>
                                </div>
                            </div>
                            <div class="shrink-0 flex items-center gap-1">
                                <a href="{{ route('admin.students.show', $student) }}" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Rekap">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline"
                                      onsubmit="return confirmDeleteStudent(event, this, '{{ $student->full_name }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="mt-2.5 flex items-center gap-1 font-body-sm text-body-sm text-on-surface-variant">
                            <span class="material-symbols-outlined text-[16px] text-outline">call</span>
                            {{ $student->parent->phone ?? '-' }}
                        </p>

                        @forelse ($pivots as $class)
                            @php
                                $total = $class->program->total_sessions;
                                $left = $total === null ? null : max(0, $total - $class->pivot->sessions_completed);
                            @endphp
                            <div class="mt-3 rounded-lg bg-surface-container-low/40 border border-outline-variant/30 p-3 space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-body-sm text-body-sm text-on-surface truncate">{{ $class->name }}</span>
                                    <span class="font-body-sm text-body-sm text-outline shrink-0">{{ $class->pivot->sessions_completed }}/{{ $class->program->total_sessions ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container/60 text-on-primary">{{ $class->level_label ?? '-' }}</span>
                                    @if (! $class->pivot->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">{{ $class->pivot->renewal_status === 'pindah' ? 'Pindah' : 'Berhenti' }}</span>
                                    @elseif ($class->pivot->renewal_status === 'lanjut')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E6F8FC] text-secondary">Lanjut</span>
                                    @elseif ($left === null)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Bulanan</span>
                                    @elseif ($left === 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Paket habis</span>
                                    @elseif ($left <= 1)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">Sisa {{ $left }}x</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Sisa {{ $left }}x</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="mt-3 font-body-sm text-body-sm text-outline">Belum ada paket aktif.</p>
                        @endforelse
                    </div>
                @empty
                    <div class="p-8 text-center font-body-sm text-body-sm text-outline">Belum ada data siswa.</div>
                @endforelse
            </div>

            {{-- Desktop: tabel --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Orang Tua</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">No. HP</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Paket</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Level</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Status Paket</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($students as $student)
                            @php
                                $pivots = $student->classes->filter(fn ($c) => $c->pivot->is_active);
                                if ($pivots->isEmpty() && in_array($status, ['berhenti', 'pindah'], true)) {
                                    $pivots = $student->classes->filter(fn ($c) => $c->pivot->renewal_status === $status);
                                }
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $student->full_name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $student->parent->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $student->parent->phone ?? '-' }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface space-y-1">
                                    @forelse ($pivots as $class)
                                        <div class="flex items-center gap-2">
                                            <span class="font-body-sm text-body-sm text-on-surface">{{ $class->name }}</span>
                                            <span class="font-body-sm text-body-sm text-outline">{{ $class->pivot->sessions_completed }}/{{ $class->program->total_sessions ?? '-' }}</span>
                                        </div>
                                    @empty
                                        <span class="font-body-sm text-body-sm text-outline">-</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3 space-y-1">
                                    @forelse ($pivots as $class)
                                        <div>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container/60 text-on-primary">{{ $class->level_label ?? '-' }}</span>
                                        </div>
                                    @empty
                                        <span class="font-body-sm text-body-sm text-outline">-</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3 space-y-1">
                                    @forelse ($pivots as $class)
                                        @php
                                            $total = $class->program->total_sessions;
                                            $left = $total === null ? null : max(0, $total - $class->pivot->sessions_completed);
                                        @endphp
                                        <div>
                                            @if (! $class->pivot->is_active)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">{{ $class->pivot->renewal_status === 'pindah' ? 'Pindah' : 'Berhenti' }}</span>
                                            @elseif ($class->pivot->renewal_status === 'lanjut')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E6F8FC] text-secondary">Lanjut</span>
                                            @elseif ($left === null)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Bulanan</span>
                                            @elseif ($left === 0)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Paket habis</span>
                                            @elseif ($left <= 1)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">Sisa {{ $left }}x</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Sisa {{ $left }}x</span>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="font-body-sm text-body-sm text-outline">-</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.students.show', $student) }}" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Lihat Rekap">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.students.edit', $student) }}" class="p-2 rounded-lg text-outline hover:text-primary hover:bg-surface-container-low transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline"
                                              onsubmit="return confirmDeleteStudent(event, this, '{{ $student->full_name }}')">
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
                                <td colspan="7" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada data siswa.</td>
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
                    @if ($status !== 'aktif')
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    @if ($level !== null)
                        <input type="hidden" name="level" value="{{ $level }}">
                    @endif
                </form>
                <div class="w-full">{{ $students->links('vendor.pagination.prevnext') }}</div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
