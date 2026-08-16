<x-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-headline text-headline-md text-on-surface">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        @if (session('warning'))
            <div class="bg-[#FFF8E1] text-[#8D6E00] border border-[#FFB300]/30 px-4 py-3 rounded-lg font-body-sm text-body-sm">
                {{ session('warning') }}
            </div>
        @endif

        <div class="p-4 sm:p-8 bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-[0px_4px_20px_rgba(23,32,51,0.02)]">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-sidebar-layout>
