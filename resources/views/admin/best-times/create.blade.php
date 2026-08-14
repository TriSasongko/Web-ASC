<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Isi Catatan Best Time</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">{{ $student->full_name }} — {{ $class->name }}</p>
            </div>
            <a href="{{ route('admin.classes.best-times.index', $class) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-5xl">
            <form action="{{ route('admin.classes.best-times.store', [$class, $student]) }}" method="POST" class="space-y-6">
                @csrf

                <div class="flex items-start gap-2 bg-surface-container-low rounded-lg px-4 py-3">
                    <span class="material-symbols-outlined text-[18px] text-outline mt-0.5">info</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        Format pencatatan waktu: <span class="font-bold">Menit : Detik : Mili Detik</span>.
                        Cukup ketik angkanya, titik dua (<span class="font-bold">:</span>) otomatis muncul.
                        Contoh: ketik <span class="font-bold">012537</span> → <span class="font-bold">01:25:37</span> (1 menit 25 detik 37 mili detik).
                        Isi hanya sel yang ingin dicatat.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl">
                    <div>
                        <x-input-label for="recorded_at" value="Tanggal Pencatatan" />
                        <x-text-input id="recorded_at" type="date" name="recorded_at" class="mt-1 block w-full" value="{{ old('recorded_at', now()->format('Y-m-d')) }}" required />
                        <x-input-error :messages="$errors->get('recorded_at')" class="mt-2" />
                    </div>
                </div>

                @error('times')
                    <div class="flex items-center gap-2 bg-[#FFEBEE] text-[#C62828] border border-[#C62828]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                        <span class="material-symbols-outlined text-[18px]">error</span> {{ $message }}
                    </div>
                @enderror

                @foreach (['grup1' => ['kupu_kupu', 'dada', 'punggung'], 'grup2' => ['bebas']] as $grup => $styles)
                    <div class="border-t border-outline-variant/30 pt-6">
                        <div class="overflow-x-auto rounded-xl border border-outline-variant/30">
                            <table class="w-full">
                                <thead class="bg-surface-container-low">
                                    <tr>
                                        <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-left">Gaya</th>
                                        @foreach (\App\Models\BestTime::distancesByStyle()[$styles[0]] as $distance)
                                            <th class="px-3 py-3 font-label-md text-label-md text-on-surface text-center whitespace-nowrap">{{ \App\Models\BestTime::distanceLabel($distance) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/30">
                                    @foreach ($styles as $style)
                                        <tr>
                                            <td class="px-4 py-3 font-body-md text-body-md text-on-surface whitespace-nowrap">
                                                @if ($style === 'bebas')
                                                    <span class="material-symbols-outlined text-[18px] text-primary align-middle mr-1.5">pool</span>
                                                @else
                                                    <span class="material-symbols-outlined text-[18px] text-outline align-middle mr-1.5">pool</span>
                                                @endif
                                                {{ \App\Models\BestTime::styleLabel($style) }}
                                            </td>
                                            @foreach (\App\Models\BestTime::distancesByStyle()[$style] as $distance)
                                                @php
                                                    $inputName = 'times.'.$style.'.'.$distance;
                                                    $errorKey = 'times.'.$style.'.'.$distance;
                                                @endphp
                                                <td class="px-3 py-3 text-center">
                                                    <input type="text" name="times[{{ $style }}][{{ $distance }}]" value="{{ old($inputName) }}"
                                                        placeholder="01:25:37"
                                                        maxlength="8"
                                                        inputmode="numeric"
                                                        autocomplete="off"
                                                        x-data
                                                        @input="formatTimeInput($el)"
                                                        class="w-28 mx-auto text-center bg-surface-container-low border border-outline-variant/50 rounded-lg px-2 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all placeholder:text-outline/60">
                                                    @error($errorKey)
                                                        <p class="font-body-sm text-body-sm text-error mt-1">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan Catatan</x-primary-button>
                    <a href="{{ route('admin.classes.best-times.index', $class) }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-5 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
