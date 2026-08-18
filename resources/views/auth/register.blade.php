<x-guest-card-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="font-headline text-headline-md text-primary mb-2">AantassenaSwimClub</h1>
        <p class="font-body text-body-sm text-on-surface-variant">Buat akun Anda untuk bergabung dengan platform performa elit.</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5">
        @csrf

        <!-- Full Name -->
        <div class="flex flex-col gap-1">
            <label class="text-label-sm text-on-surface" for="name">Nama Lengkap</label>
            <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="John Doe" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-1">
            <label class="text-label-sm text-on-surface" for="email">Email</label>
            <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Phone -->
        <div class="flex flex-col gap-1">
            <label class="text-label-sm text-on-surface" for="phone">No. HP</label>
            <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="08xx xxxx xxxx" />
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <!-- Full Address -->
        <div class="flex flex-col gap-1">
            <label class="text-label-sm text-on-surface" for="address">Alamat Lengkap</label>
            <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="address" name="address" type="text" value="{{ old('address') }}" placeholder="Jl. Contoh No. 12, Kecamatan, Kota" />
            <x-input-error :messages="$errors->get('address')" />
        </div>

        <!-- Passwords -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="flex flex-col gap-1">
                <label class="text-label-sm text-on-surface" for="password">Password</label>
                <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="password" name="password" type="password" placeholder="••••••••" required autocomplete="new-password" />
                <div class="flex gap-1 mt-1">
                    <div class="h-1 w-full bg-error rounded-full opacity-30"></div>
                    <div class="h-1 w-full bg-outline-variant rounded-full"></div>
                    <div class="h-1 w-full bg-outline-variant rounded-full"></div>
                </div>
                <x-input-error :messages="$errors->get('password')" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-label-sm text-on-surface" for="password_confirmation">Konfirmasi Password</label>
                <input class="w-full rounded-lg border border-outline-variant/50 px-4 py-2 text-body-md text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-outline/50" id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <!-- T&C -->
        <div class="flex items-start gap-3 mt-2" x-data="{ readTerms: @js(old('terms') ? true : false) }">
            <div class="flex items-center h-5">
                <input id="terms" name="terms" type="checkbox" value="1"
                       x-bind:disabled="!readTerms"
                       x-bind:class="readTerms ? '' : 'opacity-40 cursor-not-allowed'"
                       class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary bg-white" {{ old('terms') ? 'checked' : '' }} />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-body-sm text-on-surface-variant cursor-pointer" for="terms">
                    Saya telah membaca dan menyetujui <a class="text-primary font-semibold hover:underline" href="#" x-on:click.prevent="readTerms = true; $dispatch('open-modal', 'syarat-ketentuan')">Syarat &amp; Ketentuan</a>.
                </label>
                <p x-show="!readTerms" class="text-label-sm text-outline">Klik "Syarat &amp; Ketentuan" untuk membaca sebelum menyetujui.</p>
            </div>
        </div>
        <x-input-error :messages="$errors->get('terms')" />

        <!-- Submit -->
        <button type="submit" class="w-full bg-primary-container text-white text-label-md py-3 px-6 rounded-lg mt-4 hover:bg-primary transition-colors flex justify-center items-center gap-2 active:scale-[0.98]">
            Daftar Sekarang
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
    </form>

    <!-- Modal Syarat & Ketentuan -->
    <x-modal name="syarat-ketentuan" maxWidth="2xl">
        <div class="flex flex-col max-h-[85vh]">
            <!-- Header -->
            <div class="flex items-start justify-between gap-4 px-6 py-4 border-b border-outline-variant/30 bg-surface-container-lowest sticky top-0 z-10">
                <div>
                    <h3 class="font-headline text-headline-sm text-on-surface">Syarat &amp; Ketentuan</h3>
                    <p class="font-body-sm text-body-sm text-outline mt-1">Pendaftaran &amp; Kegiatan Latihan Renang AantassenaSwimClub</p>
                </div>
                <button type="button" @click="$dispatch('close-modal', 'syarat-ketentuan')" class="text-outline hover:text-on-surface transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 overflow-y-auto text-body-sm leading-relaxed text-on-surface-variant space-y-5">
                {!! \App\Models\LandingSetting::parseSyaratKetentuan($syaratKetentuan) !!}
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-outline-variant/30 bg-surface-container-lowest sticky bottom-0 z-10">
                <button type="button" @click="$dispatch('close-modal', 'syarat-ketentuan')"
                        class="w-full inline-flex items-center justify-center gap-2 bg-primary-container text-white text-label-md py-3 px-6 rounded-lg hover:bg-primary transition-colors">
                    Saya Sudah Membaca
                    <span class="material-symbols-outlined text-[18px]">check</span>
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Footer Link -->
    <div class="text-center mt-6 pt-6 border-t border-outline-variant/20">
        <p class="text-body-sm text-on-surface-variant">
            Sudah punya akun? <a class="text-primary font-semibold hover:underline" href="{{ route('login') }}">Masuk</a>
        </p>
    </div>
</x-guest-card-layout>
