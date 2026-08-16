<x-sidebar-layout>
    <div class="space-y-6">
        <div>
            <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">E-Raport</h2>
            <p class="font-body-sm text-body-sm text-outline mt-1">Lihat dan unduh rekap penilaian perkembangan anak Anda.</p>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            {{-- Mobile: kartu anak --}}
            <div class="md:hidden divide-y divide-outline-variant/30">
                @forelse ($students as $student)
                    @forelse ($student->classes as $class)
                        @php
                            $latest = $student->developments
                                ->where('class_id', $class->id)
                                ->sortByDesc('created_at')
                                ->first();
                        @endphp
                        <div class="p-4 hover:bg-surface-container-low/50 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="font-label-md text-label-md text-on-surface truncate">{{ $student->full_name }}</p>
                                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container/60 text-on-primary">{{ $class->level_label ?? '-' }}</span>
                                    </div>
                                    <p class="font-body-sm text-body-sm text-outline truncate mt-0.5">{{ $class->name }} · {{ $class->program->name }}</p>
                                </div>
                                <span class="shrink-0 font-label-sm text-label-sm text-on-surface-variant whitespace-nowrap">{{ $latest?->period ?? 'Belum ada' }}</span>
                            </div>
                            <div class="mt-3">
                                @if ($latest)
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('eraport.show', [$student, $latest->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            Lihat
                                        </a>
                                        <a href="{{ route('eraport.pdf', [$student, $latest->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline" title="Unduh PDF">
                                            <span class="material-symbols-outlined text-[16px]">download</span>
                                            Unduh
                                        </a>
                                    </div>
                                @else
                                    <span class="font-body-sm text-body-sm text-outline">-</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-4 font-body-sm text-body-sm text-outline">{{ $student->full_name }} (belum ada kelas aktif)</div>
                    @endforelse
                @empty
                    <div class="p-10 text-center font-body-sm text-body-sm text-outline">Belum ada data anak.</div>
                @endforelse
            </div>

            {{-- Desktop: tabel anak --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Anak</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kelas</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Level</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Program</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Periode Terakhir</th>
                            <th class="px-4 py-2 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($students as $student)
                            @forelse ($student->classes as $class)
                                @php
                                    $latest = $student->developments
                                        ->where('class_id', $class->id)
                                        ->sortByDesc('created_at')
                                        ->first();
                                @endphp
                                <tr class="hover:bg-surface-container-low/50 transition-colors">
                                    <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $student->full_name }}</td>
                                    <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $class->name }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-container/60 text-on-primary">{{ $class->level_label ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">{{ $class->program->name }}</td>
                                    <td class="px-4 py-2 font-body-sm text-body-sm text-on-surface">
                                        @if ($latest)
                                            {{ $latest->period }}
                                        @else
                                            <span class="font-body-sm text-body-sm text-outline">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($latest)
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('eraport.show', [$student, $latest->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                                    Lihat
                                                </a>
                                                <a href="{{ route('eraport.pdf', [$student, $latest->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline" title="Unduh PDF">
                                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                                    Unduh
                                                </a>
                                            </div>
                                        @else
                                            <span class="font-body-sm text-body-sm text-outline">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 font-body-sm text-body-sm text-outline">{{ $student->full_name }} (belum ada kelas aktif)</td>
                                </tr>
                            @endforelse
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Belum ada data anak.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-sidebar-layout>
