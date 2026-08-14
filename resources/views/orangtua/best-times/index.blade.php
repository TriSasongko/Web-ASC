<x-sidebar-layout>
    <div class="space-y-6">
        <div>
            <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Best Time Anak</h2>
            <p class="font-body-sm text-body-sm text-outline mt-1">Rekor pribadi waktu renang anak Anda.</p>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
            </div>
        @endif

        @forelse ($students as $student)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
                <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-headline text-headline-md shrink-0">
                        {{ strtoupper(substr($student->full_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-headline text-headline-sm text-on-surface">{{ $student->full_name }}</h3>
                        <p class="font-body-sm text-body-sm text-outline">Format: Menit : Detik : Mili Detik (contoh 01:25:37)</p>
                    </div>
                </div>

                @php
                    $studentBest = $best[$student->id] ?? [];
                    $hasData = collect($studentBest)->flatten()->isNotEmpty();
                @endphp

                @if ($hasData)
                    <div class="p-5 space-y-4">
                        @foreach (['grup1' => ['kupu_kupu', 'dada', 'punggung'], 'grup2' => ['bebas']] as $grup => $styles)
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
                                                <td class="px-4 py-3 font-body-md text-body-md text-on-surface whitespace-nowrap">{{ \App\Models\BestTime::styleLabel($style) }}</td>
                                                @foreach (\App\Models\BestTime::distancesByStyle()[$style] as $distance)
                                                    @php
                                                        $ms = $studentBest[$style][$distance] ?? null;
                                                    @endphp
                                                    <td class="px-3 py-3 text-center">
                                                        @if ($ms !== null)
                                                            <span class="inline-flex items-center gap-1 font-label-md text-label-md text-on-surface bg-primary-container/60 rounded-lg px-3 py-1.5">
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
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <span class="material-symbols-outlined text-outline text-[40px]">timer_off</span>
                        <p class="font-body-sm text-body-sm text-outline mt-2">Belum ada catatan best time untuk {{ $student->full_name }}.</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-surface-container-lowest rounded-xl border border-dashed border-outline-variant/50 p-10 text-center">
                <span class="material-symbols-outlined text-outline text-[40px]">child_care</span>
                <p class="font-body-sm text-body-sm text-outline mt-2">Belum ada anak terdaftar.</p>
            </div>
        @endforelse
    </div>
</x-sidebar-layout>
