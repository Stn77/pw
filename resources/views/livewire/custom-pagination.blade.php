<div>
    @if($paginatedData->lastPage() > 1)
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{-- Tombol Previous --}}
                <li class="page-item {{ $paginatedData->currentPage() == 1 ? 'disabled' : '' }}">
                    <button
                        class="page-link"
                        wire:click="previousPage"
                        wire:loading.attr="disabled"
                        aria-label="Previous"
                    >
                        <span aria-hidden="true">&laquo;</span>
                    </button>
                </li>

                {{-- Tombol First Page --}}
                @if($paginatedData->currentPage() > 2)
                    <li class="page-item">
                        <button
                            type="button"
                            class="page-link"
                            wire:click="gotoPage(1)"
                            wire:loading.attr="disabled"
                        >
                            1
                        </button>
                    </li>
                    @if($paginatedData->currentPage() > 3)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                {{-- Numbered Pages --}}
                @foreach($pages as $page)
                    <li class="page-item {{ $paginatedData->currentPage() == $page ? 'active' : '' }}">
                        <button
                            type="button"
                            class="page-link"
                            wire:click="gotoPage({{ $page }})"
                            wire:loading.attr="disabled"
                        >
                            {{ $page }}
                        </button>
                    </li>
                @endforeach

                {{-- Tombol Last Page --}}
                @if($paginatedData->currentPage() < $paginatedData->lastPage() - 1)
                    @if($paginatedData->currentPage() < $paginatedData->lastPage() - 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <button
                            type="button"
                            class="page-link"
                            wire:click="gotoPage({{ $paginatedData->lastPage() }})"
                            wire:loading.attr="disabled"
                        >
                            {{ $paginatedData->lastPage() }}
                        </button>
                    </li>
                @endif

                {{-- Tombol Next --}}
                <li class="page-item {{ $paginatedData->currentPage() == $paginatedData->lastPage() ? 'disabled' : '' }}">
                    <button
                        class="page-link"
                        wire:click="nextPage"
                        wire:loading.attr="disabled"
                        aria-label="Next"
                    >
                        <span aria-hidden="true">&raquo;</span>
                    </button>
                </li>
            </ul>
        </nav>

        {{-- Info halaman saat ini --}}
        <div class="text-center mt-2">
            <small class="text-muted">
                Menampilkan {{ $paginatedData->firstItem() }} - {{ $paginatedData->lastItem() }}
                dari {{ $paginatedData->total() }} data
            </small>
        </div>
    @endif
</div>
