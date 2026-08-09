<x-sidebar-layout>
    @php
        $fmt = fn ($n) => 'Rp '.number_format($n ?? 0, 0, ',', '.');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Detail Orang Tua</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">{{ $parent->name }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.parents.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Kembali
                </a>
                <a href="{{ route('admin.parents.edit', $parent) }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6">
            <div class="flex items-center justify-between gap-4 mb-6">
                <h3 class="font-headline text-headline-sm text-on-surface">Profil Orang Tua</h3>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm {{ $parent->is_active ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-error-container text-on-error-container' }}">
                    <span class="material-symbols-outlined text-[14px]">{{ $parent->is_active ? 'check_circle' : 'cancel' }}</span>
                    {{ $parent->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">Nama</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $parent->name }}</dd>
                </div>
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">Email</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $parent->email }}</dd>
                </div>
                <div>
                    <dt class="font-label-sm text-label-sm text-outline">No. HP</dt>
                    <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $parent->phone ?? '-' }}</dd>
                </div>
            </dl>

            <form action="{{ route('admin.parents.toggle-active', $parent) }}" method="POST" class="mt-6 pt-5 border-t border-outline-variant/30">
                @csrf @method('PATCH')
                <button type="submit" class="inline-flex items-center justify-center gap-2 border rounded-lg px-4 py-2.5 font-label-md text-label-md transition-all {{ $parent->is_active ? 'border-error text-error hover:bg-error-container hover:text-on-error-container' : 'border-primary text-primary hover:bg-primary-container hover:text-on-primary' }}">
                    <span class="material-symbols-outlined text-[18px]">{{ $parent->is_active ? 'person_off' : 'person_add' }}</span>
                    {{ $parent->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                </button>
            </form>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-secondary">child_care</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Anak & Enrolment</h3>
                </div>
            </div>

            <div class="p-5 space-y-4">
                @forelse ($students as $student)
                    <div class="bg-surface-container-low/30 rounded-lg border border-outline-variant/30 p-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-label-md text-label-md text-on-surface">{{ $student->full_name }}
                                @if ($student->nickname)
                                    <span class="text-outline text-body-sm">({{ $student->nickname }})</span>
                                @endif
                            </p>
                            <a href="{{ route('admin.students.show', $student) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                Lihat Rekap
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>

                        @forelse ($student->classes as $enrollment)
                            @php
                                $program = $enrollment->program;
                                $total = $program->total_sessions;
                                $left = $total === null ? null : max(0, $total - $enrollment->pivot->sessions_completed);
                            @endphp
                            <div class="ml-4 mt-2 flex flex-wrap items-center gap-2 font-body-sm text-body-sm">
                                <span class="text-on-surface">{{ $enrollment->name }} ({{ $program->name }} · {{ $fmt($program->price) }})</span>
                                <span class="text-outline">{{ $enrollment->pivot->sessions_completed }}/{{ $total ?? '-' }} pertemuan</span>

                                @if ($enrollment->pivot->renewal_status === 'berhenti')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Berhenti</span>
                                @elseif ($left !== null && $left === 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-error-container text-on-error-container">Habis</span>
                                @elseif ($left !== null && $left === 1)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#FFF8E1] text-[#B26A00]">Sisa 1x</span>
                                @elseif ($enrollment->pivot->renewal_status === 'lanjut')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E6F8FC] text-secondary">Lanjut</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm bg-[#E8F5E9] text-[#2E7D32]">Aman</span>
                                @endif
                            </div>
                        @empty
                            <p class="ml-4 mt-2 font-body-sm text-body-sm text-outline">Belum ada enrolment di kelas manapun.</p>
                        @endforelse
                    </div>
                @empty
                    <p class="font-body-sm text-body-sm text-outline">Belum ada anak terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-sidebar-layout>
