@props(['s', 'compact' => false])

<div x-data="{ open: false }" class="inline-block">
    <button @click="open = true" type="button"
        class="{{ $compact
            ? 'inline-flex items-center justify-center gap-0.5 px-1.5 py-0.5 rounded font-label-sm'
            : 'inline-flex items-center gap-1 px-2 py-1 rounded-full font-label-sm' }} text-label-sm bg-primary/10 text-primary hover:bg-primary/20 transition-all active:scale-95">
        <span class="material-symbols-outlined {{ $compact ? 'text-[12px]' : 'text-[14px]' }}">group</span>
        Detail Siswa
    </button>

    <div x-show="open" x-cloak x-transition.opacity
        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
        @click="open = false"></div>

    <div x-show="open" x-cloak x-transition
        @keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden">
            <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50">
                <div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Detail Siswa</h3>
                    <p class="font-body-sm text-body-sm text-outline mt-0.5 truncate">
                        {{ $s->schoolClass?->name ?? '-' }}
                        @if ($s->schoolClass?->level_label)
                            · {{ $s->schoolClass->level_label }}
                        @endif
                    </p>
                </div>
                <button @click="open = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <div class="px-6 py-4 max-h-72 overflow-y-auto">
                @if ($s->students->isEmpty())
                    <p class="font-body-sm text-body-sm text-outline text-center py-4">Belum ada siswa di jadwal ini.</p>
                @else
                    <p class="font-label-sm text-label-sm text-outline mb-3">{{ $s->students->count() }} siswa terdaftar:</p>
                    <ul class="space-y-2">
                        @foreach ($s->students as $student)
                            <li class="flex items-center gap-3 rounded-lg border border-outline-variant/30 bg-surface-container-low/50 px-3 py-2">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">person</span>
                                </span>
                                <div class="min-w-0">
                                    <p class="font-label-md text-label-md text-on-surface truncate">{{ $student->full_name }}</p>
                                    @if ($student->nickname)
                                        <p class="font-label-sm text-label-sm text-outline truncate">{{ $student->nickname }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="flex justify-end px-6 py-4 border-t border-outline-variant/30 bg-surface/50">
                <button @click="open = false" type="button"
                    class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
