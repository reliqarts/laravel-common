@php
    if (empty($paginated) && !empty($items)) $paginated = $items;
@endphp
@if (!empty($paginated))
    <div class="my-2 text-xs text-right">
        <span>{{ __('Showing :firstItem to :lastItem of :total', ['firstItem' => $paginated->firstItem(), 'lastItem' => $paginated->lastItem(), 'total' => $paginated->total()]) }}</span>
    </div>
@endif
