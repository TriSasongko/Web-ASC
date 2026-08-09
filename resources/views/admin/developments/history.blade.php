<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Riwayat Penilaian — {{ $student->full_name }}</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Seluruh evaluasi perkembangan yang pernah dicatat.</p>
            </div>
            <a href="{{ route('admin.classes.developments.index', $class) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        @forelse ($developments as $dev)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex flex-wrap items-center gap-3 justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">event_note</span>
                        <h3 class="font-headline text-headline-sm text-on-surface">{{ $dev->period }}</h3>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('eraport.show', [$student, $dev->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                            <span class="material-symbols-outlined text-[16px]">description</span>
                            Lihat E-Raport
                        </a>
                        <form action="{{ route('admin.developments.destroy', $dev) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 text-error font-label-md text-label-md hover:underline">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-5 space-y-5">
                    <div>
                        <p class="font-label-md text-label-md text-on-surface mb-2">Penilaian Umum</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach (\App\Models\Development::umumAspects() as $key => $label)
                                <div class="flex items-center justify-between gap-3 bg-surface-container-low rounded-lg px-3 py-2">
                                    <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $label }}</span>
                                    <strong class="font-label-md text-label-md text-primary">{{ \App\Models\Development::scoreLabel($dev->$key) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="font-label-md text-label-md text-on-surface mb-2">Penilaian Aspek Khusus</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach (\App\Models\Development::khususAspects() as $key => $label)
                                <div class="flex items-center justify-between gap-3 bg-surface-container-low rounded-lg px-3 py-2">
                                    <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $label }}</span>
                                    <strong class="font-label-md text-label-md text-primary">{{ \App\Models\Development::scoreLabel($dev->$key) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($dev->coach_note)
                        <div class="flex items-start gap-2 bg-[#FFF8E1] text-[#B26A00] border border-[#B26A00]/20 px-4 py-3 rounded-lg">
                            <span class="material-symbols-outlined text-[18px] shrink-0">note</span>
                            <p class="font-body-sm text-body-sm">Catatan: {{ $dev->coach_note }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-8 text-center">
                <span class="material-symbols-outlined text-outline text-[40px] mb-2">inbox</span>
                <p class="font-body-sm text-body-sm text-outline">Belum ada penilaian untuk siswa ini.</p>
            </div>
        @endforelse
    </div>
</x-sidebar-layout>
