<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-error text-on-error rounded-lg font-label-md text-label-md hover:opacity-90 transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-error/50 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
