{{--
    BSD Admin — Pagination Partial
    resources/views/admin/partials/pagination.blade.php

    Renders a Laravel paginator (`$paginator`, e.g. a LengthAwarePaginator from
    ->paginate()->withQueryString()) using the site's existing .pagination /
    .page-btn / .page-info classes (public/dashboard/css/components.css),
    so it matches the decorative pagination the static mockups used to show.
    Reusable by any admin index page — just pass the paginator instance.
--}}
@if ($paginator->total() > 0)
<div class="pagination">
    @if ($paginator->onFirstPage())
        <button class="page-btn" disabled>‹</button>
    @else
        <a class="page-btn" href="{{ $paginator->previousPageUrl() }}">‹</a>
    @endif

    @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
        @if ($page == $paginator->currentPage())
            <button class="page-btn active">{{ $page }}</button>
        @else
            <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a class="page-btn" href="{{ $paginator->nextPageUrl() }}">›</a>
    @else
        <button class="page-btn" disabled>›</button>
    @endif

    <span class="page-info">
        <span class="en">Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}</span>
        <span class="ar">عرض {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} من {{ $paginator->total() }}</span>
    </span>
</div>
@endif
