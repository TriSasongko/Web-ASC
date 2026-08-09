<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 border border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary/50 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
