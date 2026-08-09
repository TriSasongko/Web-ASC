<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Detail Pendaftaran</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Verifikasi dan kelola pendaftaran siswa.</p>
            </div>
            <a href="{{ route('admin.registrations.index') }}" class="inline-flex items-center justify-center gap-2 border border-primary text-primary px-4 py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-3xl space-y-6">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-[#00687A]">family_restroom</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Data Orang Tua</h3>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Nama</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $registration->student->parent->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Email</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $registration->student->parent->email }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-label-sm text-label-sm text-outline">No. HP</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">
                            @if ($registration->student->parent->phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $registration->student->parent->phone)) }}"
                                    target="_blank" class="inline-flex items-center gap-1 text-primary font-label-md text-label-md hover:underline">
                                    {{ $registration->student->parent->phone }} (Chat WA)
                                </a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <hr class="border-outline-variant/30">

            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">child_care</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Data Anak</h3>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Nama</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $registration->student->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Tempat, Tanggal Lahir</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $registration->student->birth_place ?? '-' }},
                            {{ $registration->student->birth_date?->format('d-m-Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Jenis Kelamin</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $registration->student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                    </div>
                    <div>
                        <dt class="font-label-sm text-label-sm text-outline">Berat / Tinggi</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $registration->student->weight ?? '-' }} kg /
                            {{ $registration->student->height ?? '-' }} cm</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-label-sm text-label-sm text-outline">Alamat</dt>
                        <dd class="font-body-md text-body-md text-on-surface mt-0.5">{{ $registration->student->address ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <hr class="border-outline-variant/30">

            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">sports_soccer</span>
                    <h3 class="font-headline text-headline-sm text-on-surface">Program</h3>
                </div>
                <p class="font-body-md text-body-md text-on-surface">{{ $registration->program->name }} —
                    Rp{{ number_format($registration->program->price, 0, ',', '.') }}</p>
            </div>

            <hr class="border-outline-variant/30">

            @if ($registration->status === 'menunggu_verifikasi')
                <div class="flex gap-3">
                    <form action="{{ route('admin.registrations.accept', $registration) }}" method="POST">
                        @csrf @method('PATCH')
                        <x-primary-button onclick="return confirm('Terima pendaftaran ini?')">Terima</x-primary-button>
                    </form>

                    <x-secondary-button onclick="document.getElementById('rejectForm').classList.toggle('hidden')">Tolak</x-secondary-button>
                </div>

                <form id="rejectForm" action="{{ route('admin.registrations.reject', $registration) }}"
                    method="POST" class="hidden space-y-4 bg-surface-container-low rounded-xl border border-outline-variant/30 p-5">
                    @csrf @method('PATCH')
                    <div>
                        <x-input-label for="rejection_reason" value="Alasan Penolakan" />
                        <textarea id="rejection_reason" name="rejection_reason" placeholder="Alasan penolakan..." rows="3" class="w-full border-outline-variant rounded-lg px-3 py-2 bg-surface-container-lowest shadow-sm focus:border-primary focus:ring-primary/30 font-body-sm text-body-sm" required></textarea>
                        <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
                    </div>
                    <x-danger-button>Kirim Penolakan</x-danger-button>
                </form>
            @else
                <div class="flex flex-wrap items-center gap-2">
                    <p class="font-label-md text-label-md text-on-surface">Status:</p>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm {{ $registration->status === 'diterima' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-error-container text-on-error-container' }}">
                        {{ str_replace('_', ' ', ucfirst($registration->status)) }}
                    </span>
                    @if ($registration->rejection_reason)
                        <p class="font-body-sm text-body-sm text-on-surface">Alasan: {{ $registration->rejection_reason }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-sidebar-layout>
