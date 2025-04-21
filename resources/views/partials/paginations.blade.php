<ul class="pagination mb-0">
    {{-- First Page Link --}}
    @if ($paginator->onFirstPage())
        <li class="page-item first disabled">
            <span class="page-link"><i class="tf-icon ri-skip-back-mini-line ri-22px"></i></span>
        </li>
    @else
        <li class="page-item first">
            <a class="page-link" href="{{ $paginator->url(1) }}"><i class="tf-icon ri-skip-back-mini-line ri-22px"></i></a>
        </li>
    @endif

    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <li class="page-item prev disabled">
            <span class="page-link"><i class="tf-icon ri-arrow-left-s-line ri-22px"></i></span>
        </li>
    @else
        <li class="page-item prev">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="tf-icon ri-arrow-left-s-line ri-22px"></i></a>
        </li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <li class="page-item next">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="tf-icon ri-arrow-right-s-line ri-22px"></i></a>
        </li>
    @else
        <li class="page-item next disabled">
            <span class="page-link"><i class="tf-icon ri-arrow-right-s-line ri-22px"></i></span>
        </li>
    @endif

    @if ($paginator->currentPage() == $paginator->lastPage())
        <li class="page-item last disabled">
            <span class="page-link"><i class="tf-icon ri-skip-forward-mini-line ri-22px"></i></span>
        </li>
    @else
        <li class="page-item last">
            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}"><i class="tf-icon ri-skip-forward-mini-line ri-22px"></i></a>
        </li>
    @endif
</ul>
