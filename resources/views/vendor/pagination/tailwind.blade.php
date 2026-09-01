@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><span aria-disabled="true" aria-label="Previous">&laquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><span aria-disabled="true">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&raquo;</a></li>
            @else
                <li><span aria-disabled="true" aria-label="Next">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
