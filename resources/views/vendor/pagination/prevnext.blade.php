@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="font-body-sm text-body-sm text-outline">
            Menampilkan {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
        </p>
        <nav class="flex items-center gap-2" aria-label="Pagination">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full font-label-md text-label-md bg-surface-container text-outline cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="inline-flex items-center gap-1 px-4 py-2 rounded-full font-label-md text-label-md border border-primary text-primary hover:bg-primary-container transition-all">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    Sebelumnya
                </a>
            @endif

            <span class="font-body-sm text-body-sm text-on-surface-variant">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="inline-flex items-center gap-1 px-4 py-2 rounded-full font-label-md text-label-md border border-primary text-primary hover:bg-primary-container transition-all">
                    Selanjutnya
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full font-label-md text-label-md bg-surface-container text-outline cursor-not-allowed">
                    Selanjutnya
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </span>
            @endif
        </nav>
    </div>
@else
    <p class="font-body-sm text-body-sm text-outline">
        Menampilkan {{ $paginator->total() }} data
    </p>
@endif
