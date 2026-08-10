@props(['schedulesByDay', 'showClassLink' => true, 'manageable' => false])

<div class="overflow-x-auto pb-2">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-4 min-w-max">
        @foreach ($schedulesByDay as $day => $schedules)
            <div class="w-[240px]">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-headline text-headline-sm text-on-surface capitalize">{{ $day }}</h3>
                    <span class="font-label-sm text-label-sm text-outline">{{ $schedules->count() }} sesi</span>
                </div>
                <div class="space-y-3">
                    @forelse ($schedules as $s)
                        <div class="rounded-lg border border-outline-variant/30 bg-surface-container-lowest p-3 shadow-sm">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    @if ($showClassLink)
                                        <a href="{{ route('admin.classes.show', $s->schoolClass) }}" class="font-label-md text-label-md text-on-surface hover:text-primary truncate block">{{ $s->schoolClass?->name ?? '-' }}</a>
                                    @else
                                        <p class="font-label-md text-label-md text-on-surface truncate">{{ $s->schoolClass?->name ?? '-' }}</p>
                                    @endif
                                    <p class="font-body-sm text-body-sm text-outline">{{ $s->schoolClass?->program?->name }} · {{ $s->schoolClass?->level_label ?? '-' }}</p>
                                </div>
                                <span class="material-symbols-outlined text-primary shrink-0">pool</span>
                            </div>

                            <p class="mt-2 flex items-center gap-1 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-[16px] text-outline">schedule</span>
                                {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                            </p>
                            <p class="mt-1 flex items-center gap-1 font-body-sm text-body-sm text-on-surface">
                                <span class="material-symbols-outlined text-[16px] text-outline">location_on</span>
                                <span class="truncate">{{ $s->location ?? '-' }}</span>
                            </p>

                            @if ($s->coaches->isNotEmpty())
                                <div class="mt-2 flex items-start gap-1 flex-wrap">
                                    <span class="material-symbols-outlined text-[16px] text-outline mt-0.5">sports</span>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($s->coaches as $c)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">{{ $c->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mt-2 pt-2 border-t border-outline-variant/30">
                                <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">{{ $s->students->count() }} siswa</p>
                                <div class="max-h-32 overflow-y-auto space-y-0.5">
                                    @forelse ($s->students as $st)
                                        <p class="font-body-sm text-body-sm text-on-surface truncate">{{ $st->full_name }}</p>
                                    @empty
                                        <p class="font-body-sm text-body-sm text-outline">Belum ada siswa.</p>
                                    @endforelse
                                </div>
                            </div>

                            @if ($manageable)
                                <div class="mt-2 pt-2 border-t border-outline-variant/30 flex items-center gap-2">
                                    <div x-data="{ open: false }" class="relative flex-1">
                                        <button @click="open = ! open" type="button"
                                            class="w-full inline-flex items-center justify-center gap-1.5 bg-surface-container text-on-surface-variant px-3 py-1.5 rounded-lg font-label-sm text-label-sm hover:bg-surface-container-high transition-all active:scale-95">
                                            <span class="material-symbols-outlined text-[16px]">tune</span>
                                            Atur
                                        </button>
                                        <div x-show="open" @click.outside="open = false" x-transition
                                            class="absolute left-0 z-20 mt-2 w-80 bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-[0px_16px_48px_rgba(23,32,51,0.16)] p-5">
                                            <p class="font-label-md text-label-md text-on-surface mb-1">{{ ucfirst($s->day) }} {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} — {{ $s->schoolClass?->name }}</p>
                                            <form action="{{ route('admin.schedules.assign', $s) }}" method="POST" class="space-y-4">
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
                                    <form action="{{ route('admin.schedules.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center gap-1 text-error font-label-md text-label-md hover:underline px-2 py-1.5 rounded-lg border border-error/30 hover:bg-error/10 transition-all">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-outline-variant/40 p-4 text-center font-body-sm text-body-sm text-outline">Tidak ada sesi.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
