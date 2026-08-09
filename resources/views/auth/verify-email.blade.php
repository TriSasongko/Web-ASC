<x-guest-layout>
    <div class="mb-6">
        <h2 class="font-headline text-headline-md text-on-surface">Verifikasi Email</h2>
        <p class="font-body-sm text-body-sm text-outline mt-1">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 flex items-center gap-2 font-body-sm text-body-sm text-[#2E7D32] bg-[#E8F5E9] border border-[#2E7D32]/20 px-4 py-3 rounded-lg">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="font-label-md text-label-md text-primary hover:text-primary/80 transition-colors">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
