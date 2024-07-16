<div>
    @if ($paginator->hasPages())
        <nav class="pagination is-centered" role="navigation" aria-label="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button disabled class="pagination-previous" aria-disabled="true"
                        aria-label="@lang('pagination.previous')">
                    <
                </button>
            @else
                <button class="pagination-previous" wire:click="previousPage" wire.loading.attr="disabled" rel="prev"
                        aria-label="@lang('pagination.previous')">
                    <
                </button>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->onLastPage())
                <button disabled class="pagination-next" aria-disabled="true"
                        aria-label="@lang('pagination.next')">
                    >
                </button>
            @else
                <button class="pagination-next" wire:click="nextPage" wire.loading.attr="disabled" rel="next"
                        aria-label="@lang('pagination.next')">
                    >
                </button>
            @endif

            <ul class="pagination-list">
                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- ... Separator --}}
                    @if (is_string($element))
                        <li><span class="pagination-ellipsis">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li wire:key="paginator-page-{{ $page }}"><span
                                    class="pagination-link is-current">{{ $page }}</span></li>
                            @else
                                <li wire:key="paginator-page-{{ $page }}">
                                    <button type="button" class="pagination-link"
                                        wire:click="gotoPage({{ $page }})">{{ $page }}</button></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>

        </nav>
    @endif
</div>