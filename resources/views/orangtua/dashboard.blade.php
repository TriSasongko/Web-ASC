<x-sidebar-layout>
    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Dashboard Orang Tua</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Selamat datang, {{ auth()->user()->name }}! Pantau perkembangan dan pendaftaran anak Anda.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8">
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <div class="p-3 bg-primary-container text-on-primary rounded-xl shrink-0">
                    <span class="material-symbols-outlined">family_restroom</span>
                </div>
                <div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Selamat datang, {{ auth()->user()->name }}!</h3>
                    <p class="font-body-sm text-body-sm text-outline mt-1">Pantau program, sisa pertemuan, rekomendasi, dan E-Raport anak Anda.</p>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <h3 class="font-headline text-headline-sm text-on-surface">Program & Sisa Pertemuan Anak</h3>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($students as $student)
                    <div class="rounded-xl border border-outline-variant/30 p-5 hover:bg-surface-container-low/50 transition-colors">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-surface-container text-on-surface-variant flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-headline text-headline-sm text-on-surface truncate">{{ $student->full_name }}</h4>
                                <p class="font-body-sm text-body-sm text-outline">Anak Anda</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @forelse ($student->classes as $enrollment)
                                @php
                                    $program = $enrollment->program;
                                    $total = $program->total_sessions;
                                    $left = $total === null ? null : max(0, $total - $enrollment->pivot->sessions_completed);
                                @endphp
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-label-md text-label-md text-on-surface">{{ $enrollment->name }} — {{ $program->name }}</span>
                                    @if ($left === null)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-surface-container text-on-surface-variant">Bulanan</span>
                                    @elseif ($left === 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Paket habis</span>
                                    @elseif ($left <= 2)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">Sisa {{ $left }}x</span>
                                    @else
                                        <span class="font-label-sm text-label-sm text-outline">Sisa {{ $left }}x</span>
                                    @endif
                                    <span class="ml-auto font-label-sm text-label-sm text-outline">{{ $fmt($program->price) }}</span>
                                </div>
                            @empty
                                <p class="font-body-sm text-body-sm text-outline">Belum ada kelas aktif.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 p-6 text-center rounded-lg border border-dashed border-outline-variant/50">
                        <p class="font-body-sm text-body-sm text-outline">Belum ada anak terdaftar.</p>
                        <a href="{{ route('orangtua.registrations.create') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline mt-2">Daftarkan anak sekarang</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <h3 class="font-headline text-headline-sm text-on-surface">Rekomendasi Naik Kelas</h3>
            </div>
            <div class="divide-y divide-outline-variant/30">
                @forelse ($recommendations as $rec)
                    <div class="p-5 hover:bg-surface-container-low/50 transition-colors">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-label-md text-label-md text-on-surface">
                                    <span class="font-headline text-headline-sm">{{ $rec->student->full_name }}</span>
                                    @if ($rec->currentClass)
                                        — {{ $rec->currentClass->name }} ({{ $rec->currentClass->level_label ?? '-' }})
                                    @endif
                                    <span class="mx-1 text-outline">→</span>
                                    <span class="font-headline text-headline-sm text-primary">{{ $rec->recommendedClass->name ?? (\App\Models\SchoolClass::levelOptions()[$rec->recommended_level] ?? 'Level '.($rec->recommended_level ?? '-')) }}</span>
                                </p>
                                <p class="font-body-sm text-body-sm text-outline mt-1">Dari: {{ $rec->from->name }} ({{ $rec->from->isAdmin() ? 'Admin' : 'Pelatih' }})</p>
                                @if ($rec->note)
                                    <p class="font-body-sm text-body-sm text-outline mt-1">Catatan: {{ $rec->note }}</p>
                                @endif
                            </div>
                            <div class="shrink-0">
                                @if ($rec->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#FFF8E1] text-[#B26A00] font-label-sm text-label-sm">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        Menunggu persetujuan admin
                                    </span>
                                @elseif ($rec->status === 'menunggu_ortu')
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#E3F2FD] text-[#1565C0] font-label-sm text-label-sm">
                                        <span class="material-symbols-outlined text-[16px]">forum</span>
                                        Menunggu konfirmasi Anda
                                    </span>
                                @elseif ($rec->status === 'diterima')
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#E8F5E9] text-[#2E7D32] font-label-sm text-label-sm">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm">
                                        <span class="material-symbols-outlined text-[16px]">close</span>
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="p-6 text-center font-body-sm text-body-sm text-outline">Belum ada rekomendasi.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <h3 class="font-headline text-headline-sm text-on-surface">E-Raport Anak</h3>
            </div>
            <div class="divide-y divide-outline-variant/30">
                @php
                    $myDevelopments = \App\Models\Development::whereHas('student', fn($q) => $q->where('parent_id', auth()->id()))
                        ->with('student')->latest()->get();
                @endphp
                @forelse ($myDevelopments as $dev)
                    <div class="p-5 flex items-center justify-between gap-4 hover:bg-surface-container-low/50 transition-colors">
                        <p class="font-label-md text-label-md text-on-surface min-w-0 truncate">
                            {{ $dev->student->full_name }} — {{ $dev->period }}
                        </p>
                        <a href="{{ route('eraport.show', [$dev->student, $dev->id]) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline shrink-0">
                            Lihat
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                @empty
                    <p class="p-6 text-center font-body-sm text-body-sm text-outline">Belum ada E-Raport tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-sidebar-layout>
