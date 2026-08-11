<x-sidebar-layout>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">Pengaturan</h2>
                <p class="font-body-sm text-body-sm text-outline mt-1">Nomor telepon dan alamat admin dipakai sebagai kontak website (footer &amp; halaman kontak).</p>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-2 bg-[#E8F5E9] text-[#2E7D32] border border-[#2E7D32]/20 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @php
            $adminPhone = \App\Models\User::where('role', 'admin')->orderBy('id')->value('phone');
            $adminAddress = \App\Models\User::where('role', 'admin')->orderBy('id')->value('address');
        @endphp

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 md:p-8 max-w-3xl">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="phone" value="Nomor Telepon / WhatsApp" />
                    <x-text-input id="phone" name="phone" class="mt-1 block w-full"
                        value="{{ old('phone', $adminPhone) }}"
                        placeholder="081234567890" required />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    <p class="mt-2 font-body-sm text-body-sm text-outline">
                        Nomor ini tersimpan pada data pengguna admin dan akan tampil di bagian footer serta halaman kontak website.
                    </p>
                </div>

                <div>
                    <x-input-label for="address" value="Alamat" />
                    <x-text-input id="address" name="address" class="mt-1 block w-full"
                        value="{{ old('address', $adminAddress) }}"
                        placeholder="Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, ..." required />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    <p class="mt-2 font-body-sm text-body-sm text-outline">
                        Alamat ini tersimpan pada data pengguna admin dan akan tampil di bagian footer serta halaman kontak website.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan Pengaturan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
