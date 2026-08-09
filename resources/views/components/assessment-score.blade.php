@props(['name', 'value' => null])

<div class="mt-1 grid grid-cols-4 gap-2">
    @foreach (\App\Models\Development::scores() as $scoreKey => $scoreLabel)
        <label class="cursor-pointer">
            <input type="radio" name="{{ $name }}" value="{{ $scoreKey }}" class="peer sr-only"
                   {{ (old($name) ?? $value) === $scoreKey ? 'checked' : '' }} required>
            <span class="block rounded-md border border-gray-300 px-3 py-2 text-center text-sm font-medium text-gray-700 transition peer-checked:border-indigo-600 peer-checked:bg-indigo-600 peer-checked:text-white">
                {{ $loop->iteration }}
            </span>
        </label>
    @endforeach
</div>
