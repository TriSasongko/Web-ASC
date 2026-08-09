<x-sidebar-layout>
    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
        $grouped = collect();
        foreach ($levels as $lv => $label) {
            $grouped[$lv] = $students->filter(fn ($s) => $s->classes->first()?->level === $lv)->values();
        }
        $noClass = $students->filter(fn ($s) => $s->classes->isEmpty());

        $defaultTab = null;
        foreach ($levels as $lv => $label) {
            if (($grouped[$lv] ?? collect())->isNotEmpty()) {
                $defaultTab = (string) $lv;
                break;
            }
        }
        $defaultTab ??= $noClass->isNotEmpty() ? 'noclass' : '0';
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Perkembangan Siswa</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola penilaian perkembangan seluruh siswa, dikelompokkan berdasarkan level.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
            </div>
        @endif

        <div x-data="{ tab: @js($defaultTab) }" class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50">
                <div class="flex flex-wrap gap-2">
                    @foreach ($levels as $lv => $label)
                        @php $count = ($grouped[$lv] ?? collect())->count(); @endphp
                        <button type="button" @click="tab = @js((string) $lv)"
                            :class="tab === @js((string) $lv) ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-label-md text-label-md transition-all">
                            <span class="material-symbols-outlined text-[18px]">assessment</span>
                            Level {{ $lv }} — {{ $label }}
                            <span :class="tab === @js((string) $lv) ? 'bg-white/20 text-on-primary' : 'bg-primary-container text-on-primary'"
                                class="inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full font-label-sm text-label-sm">{{ $count }}</span>
                        </button>
                    @endforeach
                    @if ($noClass->isNotEmpty())
                        <button type="button" @click="tab = 'noclass'"
                            :class="tab === 'noclass' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-label-md text-label-md transition-all">
                            <span class="material-symbols-outlined text-[18px]">group_off</span>
                            Tanpa Kelas
                            <span :class="tab === 'noclass' ? 'bg-white/20 text-on-primary' : 'bg-surface-container text-on-surface-variant'"
                                class="inline-flex items-center justify-center min-w-6 h-6 px-1.5 rounded-full font-label-sm text-label-sm">{{ $noClass->count() }}</span>
                        </button>
                    @endif
                </div>
            </div>

            @foreach ($levels as $lv => $label)
                @php $levelStudents = $grouped[$lv] ?? collect(); @endphp
                <div x-show="tab === @js((string) $lv)" style="{{ $defaultTab === (string) $lv ? '' : 'display: none' }}">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-surface-container-low">
                                <tr>
                                    <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Nama Siswa</th>
                                    <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Kelas</th>
                                    <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Pertemuan</th>
                                    <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Status Paket</th>
                                    <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/30">
                                @forelse ($levelStudents as $student)
                                    @php
                                        $primary = $student->classes->first();
                                        $total = $primary?->program->total_sessions;
                                        $left = $total === null ? null : max(0, $total - $primary->pivot->sessions_completed);
                                        $studentLevel = $primary?->level;
                                        $candidateClasses = $primary
                                            ? $allClasses->filter(fn ($c) => $c->program_id === $primary->program_id && $c->level > $studentLevel)
                                            : collect();
                                        $availableLevels = $studentLevel
                                            ? array_filter($levels, fn ($l) => $l > $studentLevel, ARRAY_FILTER_USE_KEY)
                                            : [];
                                    @endphp
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $student->full_name }}</td>
                                        <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">
                                            @if ($primary)
                                                {{ $primary->name }}
                                                <span class="text-outline">({{ $fmt($primary->program->price) }})</span>
                                            @else
                                                <span class="text-outline">Tanpa kelas aktif</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $primary ? $primary->pivot->sessions_completed.'/'.($total ?? '-') : '-' }}</td>
                                        <td class="px-4 py-2">
                                            @if ($left === null)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Bulanan</span>
                                            @elseif ($left >= 2)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Aman (sisa {{ $left }}x)</span>
                                            @elseif ($left === 1)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">Sisa 1 pertemuan</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Paket habis</span>
                                            @endif
                                            @if ($primary?->pivot->renewal_status === 'lanjut')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-[#E6F8FC] text-secondary ml-1">Lanjut</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            @if ($primary)
                                                <div class="flex items-center gap-2 whitespace-nowrap">
                                                    <a href="{{ route('pelatih.developments.create', [$primary, $student]) }}" class="inline-flex items-center gap-1 text-primary font-label-sm text-label-sm hover:underline">Isi Penilaian</a>
                                                    <a href="{{ route('pelatih.developments.history', [$primary, $student]) }}" class="inline-flex items-center gap-1 text-primary font-label-sm text-label-sm hover:underline">Riwayat</a>

                                                    <div x-data="{ open: false }" class="relative inline-block">
                                                        <button @click="open = ! open" type="button"
                                                            class="inline-flex items-center gap-1 bg-[#FFB300] text-white px-2.5 py-1 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">
                                                            Rekomendasi ↑
                                                        </button>
                                                        <div x-show="open" @click.outside="open = false" x-transition
                                                            class="absolute right-0 z-20 mt-2 w-72 bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-lg p-4">
                                                            <p class="font-label-md text-label-md text-on-surface mb-3">Rekomendasi Naik Level — {{ $student->full_name }}</p>
                                                            <form action="{{ route('pelatih.recommendations.store', [$primary, $student]) }}" method="POST" class="space-y-3">
                                                                @csrf
                                                                <select name="recommended_class_id" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                                                    <option value="">-- Kelas target (opsional) --</option>
                                                                    @foreach ($candidateClasses as $c)
                                                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->level_label ?? '-' }})</option>
                                                                    @endforeach
                                                                </select>
                                                                <select name="recommended_level" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                                                                    <option value="">-- Atau level target (opsional) --</option>
                                                                    @foreach ($availableLevels as $levelValue => $levelLabel)
                                                                        <option value="{{ $levelValue }}">{{ $levelLabel }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <textarea name="note" rows="2" placeholder="Catatan (opsional)" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all"></textarea>
                                                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[#FFB300] text-white px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">Simpan Rekomendasi</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="font-body-sm text-body-sm text-outline">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center font-body-sm text-body-sm text-outline">Belum ada siswa aktif di level ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            @if ($noClass->isNotEmpty())
                <div x-show="tab === 'noclass'" style="display: none">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <tbody class="divide-y divide-outline-variant/30">
                                @foreach ($noClass as $student)
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $student->full_name }}</td>
                                        <td class="px-4 py-2 font-body-sm text-body-sm text-outline">Tanpa kelas aktif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-sidebar-layout>
