@props(['s' => null, 'coaches' => []])

<div x-show="open" x-cloak x-transition.opacity
    class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px]"
    @click="open = false"></div>

<div x-show="open" x-cloak x-transition
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-3 px-6 py-4 border-b border-outline-variant/30 bg-surface/50 sticky top-0 z-10">
            <div>
                <h3 class="font-headline text-headline-sm text-on-surface">Atur Pelatih & Siswa</h3>
                <p class="font-body-sm text-body-sm text-outline mt-0.5">{{ ucfirst($s->day) }} {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} — {{ $s->schoolClass?->name }}</p>
            </div>
            <button @click="open = false" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.schedules.assign', $s) }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <x-input-label value="Pelatih" />
                <div class="max-h-40 overflow-y-auto space-y-1.5 border border-outline-variant/30 rounded-lg p-2">
                    @forelse ($coaches as $c)
                        <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low px-1.5 py-1 rounded">
                            <input type="checkbox" name="coach_ids[]" value="{{ $c->id }}" @checked($s->coaches->contains($c->id)) class="rounded border-outline-variant text-primary focus:ring-primary/40">
                            {{ $c->name }}
                        </label>
                    @empty
                        <p class="font-body-sm text-body-sm text-outline px-1">Belum ada pelatih aktif.</p>
                    @endforelse
                </div>
            </div>
            <div>
                <x-input-label value="Siswa kelas" />
                <div class="max-h-40 overflow-y-auto space-y-1.5 border border-outline-variant/30 rounded-lg p-2">
                    @php
                        $classStudents = $s->schoolClass?->students?->where('pivot.is_active', true);
                    @endphp
                    @forelse ($classStudents as $st)
                        <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low px-1.5 py-1 rounded">
                            <input type="checkbox" name="student_ids[]" value="{{ $st->id }}" @checked($s->students->contains($st->id)) class="rounded border-outline-variant text-primary focus:ring-primary/40">
                            {{ $st->full_name }}
                        </label>
                    @empty
                        <p class="font-body-sm text-body-sm text-outline px-1">Belum ada siswa di kelas ini.</p>
                    @endforelse
                </div>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-3 py-2 rounded-lg font-label-sm text-label-sm hover:opacity-90 transition-all active:scale-95">
                <span class="material-symbols-outlined text-[16px]">save</span>
                Simpan
            </button>
        </form>
    </div>
</div>
