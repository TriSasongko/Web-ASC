<x-sidebar-layout>
    <div class="space-y-6">
        <div>
            <h2 class="font-headline text-headline-lg-mobile md:text-headline-lg text-on-surface">
                {{ __('Dashboard') }}
            </h2>
        </div>

        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)] p-6 flex items-center gap-4">
            <div class="p-3 bg-[#E6F8FC] text-secondary rounded-lg">
                <span class="material-symbols-outlined">verified_user</span>
            </div>
            <div>
                <h3 class="font-headline text-headline-sm text-on-surface">{{ __("You're logged in!") }}</h3>
                <p class="font-body-sm text-body-sm text-outline mt-1">Selamat datang di ASC Academy.</p>
            </div>
        </div>
    </div>
</x-sidebar-layout>
