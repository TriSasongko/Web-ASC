@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full border-outline-variant rounded-lg px-3 py-2 bg-surface-container-lowest shadow-sm focus:border-primary focus:ring-primary/30']) }}>
