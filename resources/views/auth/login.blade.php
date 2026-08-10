<x-guest-layout>
    <div class="mb-10 text-center md:text-left">
        <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Selamat Datang</h2>
        <p class="font-body text-body-sm text-on-surface-variant">Silakan masuk ke akun Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label class="block text-label-md text-on-surface" for="email">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline-variant">mail</span>
                </div>
                <input class="block w-full pl-10 pr-3 py-3 border border-outline-variant rounded-lg bg-surface-bright text-on-surface text-body-md focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none transition-shadow shadow-sm placeholder:text-outline/50" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label class="block text-label-md text-on-surface" for="password">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline-variant">lock</span>
                </div>
                <input class="block w-full pl-10 pr-10 py-3 border border-outline-variant rounded-lg bg-surface-bright text-on-surface text-body-md focus:ring-2 focus:ring-primary focus:border-primary focus:outline-none transition-shadow shadow-sm placeholder:text-outline/50" id="password" name="password" type="password" placeholder="••••••••" required autocomplete="current-password" />
                <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-primary transition-colors focus:outline-none" id="togglePassword" type="button">
                    <span class="material-symbols-outlined" id="visibilityIcon">visibility_off</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center cursor-pointer">
                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-outline-variant rounded bg-surface-bright" />
                <span class="ml-2 text-body-sm text-on-surface-variant">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-label-md text-primary hover:text-primary-container transition-colors" href="{{ route('password.request') }}">Lupa Password?</a>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-label-md text-on-primary bg-primary-container hover:bg-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary active:scale-[0.98] transition-all duration-200">
                Masuk
                <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
            </button>
        </div>
    </form>

    <!-- Registration Link -->
    <div class="mt-8 text-center">
        <p class="text-body-sm text-on-surface-variant">
            Belum punya akun?
            <a class="text-label-md text-primary hover:text-primary-container transition-colors ml-1" href="{{ route('register') }}">Daftar Akun Baru</a>
        </p>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const visibilityIcon = document.getElementById('visibilityIcon');

        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                visibilityIcon.textContent = type === 'password' ? 'visibility_off' : 'visibility';
            });
        }
    </script>
</x-guest-layout>
