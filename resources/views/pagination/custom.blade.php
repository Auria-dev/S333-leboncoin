@if ($paginator->hasPages())
    <nav class="custom-pagination">
        @if ($paginator->onFirstPage())
            <span class="page-item disabled">
                &laquo; Précédent
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-item" rel="prev">
                &laquo; Précédent
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-item disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-item active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-item">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-item" rel="next">
                Suivant &raquo;
            </a>
        @else
            <span class="page-item disabled">
                Suivant &raquo;
            </span>
        @endif
    </nav>
@endif