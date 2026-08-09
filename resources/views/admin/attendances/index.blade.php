<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Absensi — Semua Kelas</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Kelola dan isi absensi latihan untuk setiap kelas.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] overflow-hidden">
            <div class="p-5 border-b border-outline-variant/30 bg-surface/50 flex flex-col sm:flex-row sm:items-center gap-4 sm:justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">fact_check</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Daftar Kelas</h3>
                </div>
                <form method="GET">
                    <select name="coach_id" onchange="this.form.submit()" class="bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2.5 font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                        <option value="">-- Semua Coach --</option>
                        @foreach ($coaches as $coach)
                            <option value="{{ $coach->id }}" {{ request('coach_id') == $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Nama Kelas</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Program</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Coach</th>
                            <th class="px-4 py-3 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse ($classes as $class)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->program->name }}</td>
                                <td class="px-4 py-3 font-body-sm text-body-sm text-on-surface">{{ $class->coach->name }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('admin.attendances.create', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                            <span class="material-symbols-outlined text-[16px]">fact_check</span>
                                            Input Absensi
                                        </a>
                                        <a href="{{ route('admin.attendances.history', $class) }}" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                            <span class="material-symbols-outlined text-[16px]">history</span>
                                            Riwayat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center font-body-sm text-body-sm text-outline">Belum ada kelas aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-sidebar-layout>
