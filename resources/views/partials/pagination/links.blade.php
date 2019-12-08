@php
    $paginatorAppends = $paginatorAppends ?? [];
    $paginatorOnEachSide = $paginatorOnEachSide ?? 5;
    if (empty($paginated) && !empty($items)) $paginated = $items;
@endphp
@if (!empty($paginated))
    {!! $paginated->onEachSide($paginatorOnEachSide)->appends($paginatorAppends)->links() !!}
@endif
