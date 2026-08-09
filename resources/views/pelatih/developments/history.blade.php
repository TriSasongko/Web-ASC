<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Riwayat Penilaian</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">{{ $student->full_name }} — {{ $class->name }}</p>
            </div>
            <a href="{{ route('pelatih.developments.create', [$class, $student]) }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Isi Penilaian
            </a>
        </div>

        @forelse ($developments as $dev)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">assessment</span>
                        <h3 class="font-headline text-headline-sm text-on-surface">{{ $dev->period }}</h3>
                    </div>
                    <a href="{{ route('eraport.show', [$student, $dev->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline shrink-0">
                        Lihat E-Raport
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>

                <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider mb-2">Penilaian Umum</p>
                <div class="grid grid-cols-2 gap-2 mb-4">
                    @foreach (\App\Models\Development::umumAspects() as $key => $label)
                        <div class="bg-surface-container-low rounded-lg px-4 py-3">
                            <p class="font-body-sm text-body-sm text-outline">{{ $label }}</p>
                            <p class="font-label-md text-label-md text-on-surface mt-0.5">{{ \App\Models\Development::scoreLabel($dev->$key) }}</p>
                        </div>
                    @endforeach
                </div>

                <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider mb-2">Penilaian Gaya Renang</p>
                <div class="space-y-3 mb-4">
                    @foreach (\App\Models\Development::styles() as $style => $styleLabel)
                        <div class="rounded-xl border border-outline-variant/30 bg-surface-container-low/40 p-3">
                            <p class="font-label-md text-label-md text-on-surface mb-2">{{ $styleLabel }}</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach (\App\Models\Development::khususAspects() as $aspect => $label)
                                    @php
                                        $key = \App\Models\Development::styleAspectKey($style, $aspect);
                                    @endphp
                                    <div class="bg-surface-container-low rounded-lg px-4 py-3">
                                        <p class="font-body-sm text-body-sm text-outline">{{ $label }}</p>
                                        <p class="font-label-md text-label-md text-on-surface mt-0.5">{{ \App\Models\Development::scoreLabel($dev->$key) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($dev->coach_note)
                    <div class="flex items-start gap-2 bg-[#FFF8E1] text-[#B26A00] rounded-lg px-4 py-3">
                        <span class="material-symbols-outlined text-[18px] mt-0.5">sticky_note_2</span>
                        <p class="font-body-sm text-body-sm">Catatan: {{ $dev->coach_note }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-6 text-center rounded-xl border border-dashed border-outline-variant/50">
                <p class="font-body-sm text-body-sm text-outline">Belum ada penilaian untuk siswa ini.</p>
            </div>
        @endforelse

        <a href="{{ route('pelatih.developments.index') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
    </div>
</x-sidebar-layout>
