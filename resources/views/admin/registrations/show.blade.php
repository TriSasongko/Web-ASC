<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pendaftaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">

                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Data Orang Tua</h3>
                    <p>Nama: {{ $registration->student->parent->name }}</p>
                    <p>Email: {{ $registration->student->parent->email }}</p>
                    <p>
                        No. HP:
                        @if ($registration->student->parent->phone)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $registration->student->parent->phone)) }}"
                                target="_blank" class="text-green-600 underline">
                                {{ $registration->student->parent->phone }} (Chat WA)
                            </a>
                        @else
                            -
                        @endif
                    </p>
                </div>

                <hr>

                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Data Anak</h3>
                    <p>Nama: {{ $registration->student->full_name }}</p>
                    <p>Tempat, Tanggal Lahir: {{ $registration->student->birth_place ?? '-' }},
                        {{ $registration->student->birth_date?->format('d-m-Y') ?? '-' }}</p>
                    <p>Jenis Kelamin: {{ $registration->student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    <p>Berat / Tinggi: {{ $registration->student->weight ?? '-' }} kg /
                        {{ $registration->student->height ?? '-' }} cm</p>
                    <p>Alamat: {{ $registration->student->address ?? '-' }}</p>
                </div>

                <hr>

                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Program</h3>
                    <p>{{ $registration->program->name }} —
                        Rp{{ number_format($registration->program->price, 0, ',', '.') }}</p>
                </div>

                <hr>

                @if ($registration->status === 'menunggu_verifikasi')
                    <div class="flex gap-2">
                        <form action="{{ route('admin.registrations.accept', $registration) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md"
                                onclick="return confirm('Terima pendaftaran ini?')">Terima</button>
                        </form>

                        <button type="button"
                            onclick="document.getElementById('rejectForm').classList.toggle('hidden')"
                            class="px-4 py-2 bg-red-600 text-white rounded-md">Tolak</button>
                    </div>

                    <form id="rejectForm" action="{{ route('admin.registrations.reject', $registration) }}"
                        method="POST" class="hidden space-y-2">
                        @csrf @method('PATCH')
                        <textarea name="rejection_reason" placeholder="Alasan penolakan..." class="w-full border-gray-300 rounded-md" required></textarea>
                        <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">Kirim
                            Penolakan</button>
                    </form>
                @else
                    <p class="font-semibold">
                        Status:
                        <span class="{{ $registration->status === 'diterima' ? 'text-green-700' : 'text-red-700' }}">
                            {{ str_replace('_', ' ', ucfirst($registration->status)) }}
                        </span>
                    </p>
                    @if ($registration->rejection_reason)
                        <p>Alasan: {{ $registration->rejection_reason }}</p>
                    @endif
                @endif

                <a href="{{ route('admin.registrations.index') }}" class="inline-block text-gray-600 mt-4">←
                    Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
