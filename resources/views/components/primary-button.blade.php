<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-container text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 transition-all hover:scale-[0.98] shadow-sm active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary/50 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
