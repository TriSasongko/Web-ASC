<x-sidebar-layout>
    @php
        $referer = request()->headers->get('referer');
        $backUrl = ($referer && parse_url($referer, PHP_URL_HOST) === request()->getHost() && $referer !== url()->current())
            ? $referer
            : route('admin.classes.developments.index', $class);
    @endphp
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Isi Penilaian — {{ $student->full_name }}</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Isi evaluasi perkembangan untuk periode tertentu.</p>
            </div>
            <a href="{{ $backUrl }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8">
            <form action="{{ route('admin.classes.developments.store', [$class, $student]) }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="period" value="Periode" />
                        <x-text-input id="period" name="period"
                                      placeholder="Contoh: Agustus 2026 / Paket 1" value="{{ old('period') }}" required />
                        <x-input-error :messages="$errors->get('period')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center gap-2 bg-surface-container-low rounded-lg px-4 py-3">
                    <span class="material-symbols-outlined text-outline text-[18px]">info</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        @foreach (\App\Models\Development::scores() as $scoreKey => $scoreLabel){{ $loop->first ? '' : ', ' }}{{ $loop->iteration }} = {{ $scoreLabel }}@endforeach
                    </p>
                </div>

                <div class="border-t border-outline-variant/30 pt-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary text-[20px]">insights</span>
                        <h3 class="font-headline text-headline-sm text-on-surface">Penilaian Umum</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach (\App\Models\Development::umumAspects() as $key => $label)
                            <div>
                                <x-input-label :for="$key" :value="$label" />
                                <x-assessment-score :name="$key" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-outline-variant/30 pt-6" x-data="{ tab: @js(array_key_first(\App\Models\Development::styles())) }">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary text-[20px]">sports_soccer</span>
                        <h3 class="font-headline text-headline-sm text-on-surface">Penilaian Gaya Renang</h3>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach (\App\Models\Development::styles() as $style => $styleLabel)
                            <button type="button" @click="tab = @js($style)"
                                :class="tab === @js($style) ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-label-md text-label-md transition-all">
                                <span class="material-symbols-outlined text-[18px]">pool</span>
                                {{ $styleLabel }}
                            </button>
                        @endforeach
                    </div>

                    @foreach (\App\Models\Development::styles() as $style => $styleLabel)
                        <div x-show="tab === @js($style)" style="{{ $style === array_key_first(\App\Models\Development::styles()) ? '' : 'display: none' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach (\App\Models\Development::khususAspects() as $aspect => $label)
                                    @php
                                        $key = \App\Models\Development::styleAspectKey($style, $aspect);
                                    @endphp
                                    <div>
                                        <x-input-label :for="$key" :value="$label" />
                                        <x-assessment-score :name="$key" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <x-input-label for="coach_note" value="Catatan Coach" />
                    <textarea id="coach_note" name="coach_note" rows="3" class="mt-1 block w-full border-outline-variant rounded-lg px-3 py-2 bg-surface-container-lowest shadow-sm focus:border-primary focus:ring-primary/30">{{ old('coach_note') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan Penilaian</x-primary-button>
                    <a href="{{ route('admin.classes.developments.index', $class) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
