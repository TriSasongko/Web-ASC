@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 font-body-sm text-body-sm text-[#2E7D32] bg-[#E8F5E9] border border-[#2E7D32]/20 px-4 py-3 rounded-lg']) }}>
        <span class="material-symbols-outlined text-[18px]">check_circle</span>
        {{ $status }}
    </div>
@endif
