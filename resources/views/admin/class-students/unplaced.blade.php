<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Siswa Belum Ditempatkan</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Tempatkan siswa ke kelas yang sesuai.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-2 bg-error-container text-on-error-container border border-error/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">error</span>
                {{ session('error') }}
            </div>
        @endif

        <div class="flex items-center gap-2 bg-secondary-container/30 text-secondary px-4 py-3 rounded-lg font-body-sm text-body-sm">
            <span class="material-symbols-outlined text-[18px]">info</span>
            Siswa baru defaultnya ditempatkan di kelas <strong>Beginner</strong>. Anda dapat memilih kelas lain sesuai kebutuhan.
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Program</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tempatkan ke Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($registrations as $reg)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $reg->student->full_name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $reg->program->name }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $classes = $reg->program->classes->where('is_active', true)->sortBy('level');
                                        $defaultClass = $classes->firstWhere('level', \App\Models\SchoolClass::LEVEL_BEGINNER) ?? $classes->first();
                                    @endphp
                                    <form action="{{ route('admin.class-students.place', $reg) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                        @csrf
                                        <select name="class_id" class="bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id') ? (old('class_id') == $class->id ? 'selected' : '') : ($defaultClass && $class->id === $defaultClass->id ? 'selected' : '') }}>
                                                    {{ $class->name }} ({{ $class->level_label }} — {{ $class->coach->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="inline-flex items-center justify-center gap-2 bg-primary-container text-on-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95">Tempatkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-10 text-center font-body-sm text-body-sm text-outline">Tidak ada siswa yang perlu ditempatkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke Kelas
        </a>
    </div>
</x-sidebar-layout>
