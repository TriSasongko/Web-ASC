@props(['name', 'value' => null])

<div class="mt-1 grid grid-cols-4 gap-2">
    @foreach (\App\Models\Development::scores() as $scoreKey => $scoreLabel)
        <label class="relative cursor-pointer">
            <input type="radio" name="{{ $name }}" value="{{ $scoreKey }}" class="peer absolute inset-0 opacity-0 cursor-pointer"
                   {{ (old($name) ?? $value) === $scoreKey ? 'checked' : '' }} required>
            <span class="block rounded-lg border border-outline-variant/50 bg-surface-container-low px-3 py-2 text-center font-label-md text-label-md text-on-surface-variant transition peer-checked:border-primary peer-checked:bg-primary peer-checked:text-on-primary peer-checked:shadow-sm peer-focus-visible:ring-2 peer-focus-visible:ring-primary/50">
                {{ $loop->iteration }}
            </span>
        </label>
    @endforeach
</div>
