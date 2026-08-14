<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Best Time — {{ $student->full_name }}</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">{{ $class->name }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.classes.best-times.create', [$class, $student]) }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Isi Catatan
                </a>
                <a href="{{ route('admin.classes.best-times.index', $class) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
            <div class="flex items-center gap-2 mb-5">
                <span class="material-symbols-outlined text-primary">emoji_events</span>
                <h3 class="font-headline text-headline-sm text-on-surface">Rekor Pribadi (Waktu Terbaik)</h3>
            </div>

            <div class="overflow-x-auto rounded-xl border border-outline-variant/30">
                <table class="w-full">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Gaya</th>
                            @foreach (\App\Models\BestTime::allDistances() as $distance)
                                <th class="px-3 py-3 font-label-md text-label-md text-on-surface text-center whitespace-nowrap">{{ \App\Models\BestTime::distanceLabel($distance) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @foreach (\App\Models\BestTime::styles() as $style => $styleLabel)
                            <tr>
                                <td class="px-4 py-3 font-body-md text-body-md text-on-surface whitespace-nowrap">{{ $styleLabel }}</td>
                                @foreach (\App\Models\BestTime::allDistances() as $distance)
                                    @php
                                        $ms = in_array($distance, \App\Models\BestTime::distancesByStyle()[$style])
                                            ? ($best[$style][$distance] ?? null)
                                            : null;
                                    @endphp
                                    <td class="px-3 py-3 text-center">
                                        @if ($ms !== null)
                                            <span class="inline-flex items-center gap-1 font-label-lg text-label-lg text-on-surface bg-primary-container/60 rounded-lg px-3 py-1.5">
                                                <span class="material-symbols-outlined text-[16px] text-on-primary">timer</span>
                                                {{ \App\Models\BestTime::formatTime($ms) }}
                                            </span>
                                        @else
                                            <span class="font-body-sm text-body-sm text-outline">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6"
            x-data="{ selectedDate: '', selected: 0, allChecked: false, dateCounts: @json($recordsByDate->map(fn ($r) => $r->count())) }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">history</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Riwayat Catatan</h3>
                </div>

                @if ($records->isNotEmpty())
                    <div class="flex items-center gap-4">
                        <select x-model="selectedDate"
                            @change="selected = 0; allChecked = false; $refs.historyForm.querySelectorAll('.js-history-checkbox').forEach(cb => cb.checked = false)"
                            class="rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 font-body-md text-body-md text-on-surface focus:ring-primary/30 focus:border-primary">
                            <option value="">-- Pilih Tanggal --</option>
                            @foreach ($recordsByDate as $date => $dateRecords)
                                <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') }} ({{ $dateRecords->count() }})</option>
                            @endforeach
                        </select>

                        <div x-show="selectedDate !== ''" x-cloak class="flex items-center gap-4">
                            <label class="inline-flex items-center gap-1.5 font-label-md text-label-md text-on-surface-variant cursor-pointer select-none">
                                <input type="checkbox"
                                    :checked="allChecked"
                                    @change="allChecked = $event.target.checked; selected = allChecked ? dateCounts[selectedDate] : 0; $refs.historyForm.querySelectorAll('.js-history-checkbox').forEach(cb => cb.checked = allChecked)"
                                    class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary/30">
                                Pilih semua
                            </label>
                            <button type="submit" form="best-time-history-form" :disabled="selected === 0"
                                class="inline-flex items-center justify-center gap-1.5 font-label-md text-label-md text-error border border-error/40 rounded-lg px-3 py-2 hover:bg-error/10 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                Hapus Terpilih (<span x-text="selected">0</span>)
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            @if ($records->isEmpty())
                <p class="font-body-sm text-body-sm text-outline text-center py-8">Belum ada catatan best time. Klik "Isi Catatan" untuk menambah.</p>
            @else
                <div x-show="selectedDate === ''" x-cloak class="flex flex-col items-center gap-3 text-center py-10">
                    <span class="material-symbols-outlined text-[40px] text-outline">calendar_month</span>
                    <p class="font-body-md text-body-md text-on-surface-variant">Pilih tanggal di atas untuk melihat riwayat catatan.</p>
                </div>
                <form id="best-time-history-form" x-ref="historyForm" action="{{ route('admin.classes.best-times.destroy-many', [$class, $student]) }}" method="POST" onsubmit="return confirm('Hapus catatan best time terpilih?')">
                    @csrf

                    @php $currentMonth = null; @endphp
                    @foreach ($recordsByDate as $date => $dateRecords)
                        @php
                            $month = \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('F Y');
                            $visibleRecords = $dateRecords->take(10);
                            $hiddenRecords = $dateRecords->skip(10);
                        @endphp
                        @if ($month !== $currentMonth)
                            @php $currentMonth = $month; @endphp
                            <div class="flex items-center gap-3 {{ $loop->first ? 'mb-3' : 'mt-8 mb-3' }}">
                                <span class="font-label-md text-label-md text-primary uppercase tracking-wider">{{ $month }}</span>
                                <div class="flex-1 border-t border-outline-variant/40"></div>
                            </div>
                        @endif

                        <div class="rounded-xl border border-outline-variant/30 overflow-hidden mb-5"
                            x-show="selectedDate === '{{ $date }}'"
                            x-cloak
                            x-data="{ showAll: false }">
                            <div class="px-5 py-3.5 bg-surface-container-low/70 border-b border-outline-variant/30 flex items-center justify-between">
                                <span class="font-label-lg text-label-lg text-on-surface">
                                    {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') }}
                                </span>
                                <span class="font-body-sm text-body-sm text-outline">{{ $dateRecords->count() }} catatan</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-surface-container-lowest/40">
                                        <tr>
                                            <th class="px-4 py-3 w-10">
                                                <span class="sr-only">Pilih</span>
                                            </th>
                                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Gaya</th>
                                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Jarak</th>
                                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Waktu</th>
                                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Terbaik</th>
                                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Dicatat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-outline-variant/30">
                                        @foreach ($visibleRecords as $record)
                                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" name="ids[]" value="{{ $record->id }}" class="js-history-checkbox h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary/30"
                                                        @change="selected += $event.target.checked ? 1 : -1; allChecked = selected === dateCounts[selectedDate]">
                                                    <span class="sr-only">Pilih {{ \App\Models\BestTime::styleLabel($record->style) }} {{ \App\Models\BestTime::distanceLabel($record->distance) }}</span>
                                                </td>
                                                <td class="px-4 py-3 font-body-md text-body-md text-on-surface whitespace-nowrap">
                                                    <span class="material-symbols-outlined text-[18px] text-primary align-middle mr-1.5">pool</span>
                                                    {{ \App\Models\BestTime::styleLabel($record->style) }}
                                                </td>
                                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface whitespace-nowrap">{{ \App\Models\BestTime::distanceLabel($record->distance) }}</td>
                                                <td class="px-4 py-3 font-label-md text-label-md text-on-surface tabular-nums whitespace-nowrap">{{ \App\Models\BestTime::formatTime($record->time_ms) }}</td>
                                                <td class="px-4 py-3">
                                                    @if (($best[$record->style][$record->distance] ?? null) === $record->time_ms)
                                                        <span class="inline-flex items-center gap-1 font-label-sm text-label-sm text-on-primary bg-primary rounded-full px-2.5 py-0.5">
                                                            <span class="material-symbols-outlined text-[13px]">emoji_events</span>
                                                            Terbaik
                                                        </span>
                                                    @else
                                                        <span class="font-body-sm text-body-sm text-outline">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $record->recorder?->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @if ($hiddenRecords->isNotEmpty())
                                        <tbody x-show="showAll" x-cloak class="divide-y divide-outline-variant/30">
                                            @foreach ($hiddenRecords as $record)
                                                <tr class="hover:bg-surface-container-low/40 transition-colors">
                                                    <td class="px-4 py-3">
                                                        <input type="checkbox" name="ids[]" value="{{ $record->id }}" class="js-history-checkbox h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary/30"
                                                            @change="selected += $event.target.checked ? 1 : -1; allChecked = selected === dateCounts[selectedDate]">
                                                        <span class="sr-only">Pilih {{ \App\Models\BestTime::styleLabel($record->style) }} {{ \App\Models\BestTime::distanceLabel($record->distance) }}</span>
                                                    </td>
                                                    <td class="px-4 py-3 font-body-md text-body-md text-on-surface whitespace-nowrap">
                                                        <span class="material-symbols-outlined text-[18px] text-primary align-middle mr-1.5">pool</span>
                                                        {{ \App\Models\BestTime::styleLabel($record->style) }}
                                                    </td>
                                                    <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface whitespace-nowrap">{{ \App\Models\BestTime::distanceLabel($record->distance) }}</td>
                                                    <td class="px-4 py-3 font-label-md text-label-md text-on-surface tabular-nums whitespace-nowrap">{{ \App\Models\BestTime::formatTime($record->time_ms) }}</td>
                                                    <td class="px-4 py-3">
                                                        @if (($best[$record->style][$record->distance] ?? null) === $record->time_ms)
                                                            <span class="inline-flex items-center gap-1 font-label-sm text-label-sm text-on-primary bg-primary rounded-full px-2.5 py-0.5">
                                                                <span class="material-symbols-outlined text-[13px]">emoji_events</span>
                                                                Terbaik
                                                            </span>
                                                        @else
                                                            <span class="font-body-sm text-body-sm text-outline">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $record->recorder?->name ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>

                            @if ($hiddenRecords->isNotEmpty())
                                <div class="px-5 py-3 bg-surface-container-low/50 border-t border-outline-variant/30 flex justify-center">
                                    <button type="button" @click="showAll = !showAll"
                                        class="inline-flex items-center gap-1.5 font-label-md text-label-md text-primary hover:underline">
                                        <span class="material-symbols-outlined text-[16px] transition-transform" :class="showAll ? 'rotate-180' : ''">expand_more</span>
                                        <span x-show="!showAll">Lihat {{ $hiddenRecords->count() }} catatan lainnya</span>
                                        <span x-show="showAll" x-cloak>Sembunyikan</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </form>
            @endif
        </div>
    </div>
</x-sidebar-layout>
