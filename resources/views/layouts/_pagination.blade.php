@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        @else
            <a class="prev" href="{{ $paginator->previousPageUrl() }}">
                <span class="ic-left"></span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
{{--                <li class="disabled"><a href="javascript:void(0);">{{ $element }}</a></li>--}}
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="item active">{{ $page }}</a>
                    @else
                        <a class="item" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="next" href="{{ $paginator->nextPageUrl() }}">
                <span class="ic-right"></span>
            </a>
        @else

        @endif
    </div>
@endif
