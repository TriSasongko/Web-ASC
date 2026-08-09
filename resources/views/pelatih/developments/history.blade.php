<x-sidebar-layout>
    @php
        $defaultPeriod = $developments->first()?->id;
    @endphp
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

        @if ($developments->isEmpty())
            <div class="p-6 text-center rounded-xl border border-dashed border-outline-variant/50">
                <p class="font-body-sm text-body-sm text-outline">Belum ada penilaian untuk siswa ini.</p>
            </div>
        @else
            <div x-data="{ active: {{ $defaultPeriod }} }" class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-outline-variant/30 bg-surface/50">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">event_note</span>
                            <h3 class="font-headline text-headline-sm text-on-surface">Riwayat Periode</h3>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-primary-container/60 text-on-primary">
                            {{ $developments->count() }} periode
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($developments as $devTab)
                            <button type="button" @click="active = {{ $devTab->id }}"
                                :class="active === {{ $devTab->id }} ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-label-md text-label-md transition-all">
                                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                                {{ $devTab->period }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @foreach ($developments as $devPanel)
                    <div x-show="active === {{ $devPanel->id }}" x-cloak>
                        <div class="flex items-center justify-between gap-4 px-5 py-3 border-b border-outline-variant/30 bg-surface-container-low/50">
                            <span class="font-label-md text-label-md text-on-surface-variant">{{ $devPanel->period }}</span>
                            <a href="{{ route('eraport.show', [$student, $devPanel->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline shrink-0">
                                Lihat E-Raport
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>

                        <div class="p-5 sm:p-6 space-y-5">
                            <div>
                                <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider mb-2">Penilaian Umum</p>
                                <div class="divide-y divide-outline-variant/30 rounded-xl border border-outline-variant/30 overflow-hidden bg-surface-container-low/40">
                                    @foreach (\App\Models\Development::umumAspects() as $key => $label)
                                        <div class="flex items-center justify-between gap-3 px-4 py-2.5 bg-surface-container-lowest/60">
                                            <p class="font-body-sm text-body-sm text-on-surface">{{ $label }}</p>
                                            <p class="font-label-md text-label-md text-primary">{{ \App\Models\Development::scoreLabel($devPanel->$key) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider mb-2">Penilaian Gaya Renang</p>
                                <div class="space-y-3">
                                    @foreach (\App\Models\Development::styles() as $style => $styleLabel)
                                        <div class="rounded-xl border border-outline-variant/30 bg-surface-container-low/40 overflow-hidden">
                                            <p class="font-label-md text-label-md text-on-surface px-4 py-2 bg-surface-container-low border-b border-outline-variant/30">{{ $styleLabel }}</p>
                                            <div class="divide-y divide-outline-variant/30">
                                                @foreach (\App\Models\Development::khususAspects() as $aspect => $label)
                                                    @php
                                                        $key = \App\Models\Development::styleAspectKey($style, $aspect);
                                                    @endphp
                                                    <div class="flex items-center justify-between gap-3 px-4 py-2.5 bg-surface-container-lowest/60">
                                                        <p class="font-body-sm text-body-sm text-on-surface">{{ $label }}</p>
                                                        <p class="font-label-md text-label-md text-primary">{{ \App\Models\Development::scoreLabel($devPanel->$key) }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($devPanel->coach_note)
                                <div class="flex items-start gap-2 bg-[#FFF8E1] text-[#B26A00] rounded-lg px-4 py-3">
                                    <span class="material-symbols-outlined text-[18px] mt-0.5">sticky_note_2</span>
                                    <p class="font-body-sm text-body-sm">Catatan: {{ $devPanel->coach_note }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('pelatih.developments.index') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
    </div>
</x-sidebar-layout>
