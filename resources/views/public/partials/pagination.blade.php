{{--
    BSD Public Site — Pagination Partial
    resources/views/public/partials/pagination.blade.php

    A small custom paginator matching the public site's own look (the
    default Laravel pagination view is Tailwind-based and this site loads
    no Tailwind, so it would render unstyled). See .pg-nav in
    layouts/public.blade.php for the styling.
--}}
@if ($paginator->hasPages())
<nav class="pg-nav">
    @if ($paginator->onFirstPage())
        <span class="pg-btn disabled">‹</span>
    @else
        <a class="pg-btn" href="{{ $paginator->previousPageUrl() }}">‹</a>
    @endif

    @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
        @if ($page == $paginator->currentPage())
            <span class="pg-btn active">{{ $page }}</span>
        @else
            <a class="pg-btn" href="{{ $url }}">{{ $page }}</a>
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a class="pg-btn" href="{{ $paginator->nextPageUrl() }}">›</a>
    @else
        <span class="pg-btn disabled">›</span>
    @endif
</nav>
@endif
