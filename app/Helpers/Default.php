<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;


function paginate($items, $perPage = 10, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

    $items = $items instanceof Collection ? $items : Collection::make($items);

    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
}

function paginatedResponse($collection): array
{
    if (isset($collection->resource)) {
        $resource = $collection->resource;
    } else {
        $resource = $collection;
        $collection = $collection['data'] ?? $collection?->items();
    }
    $pagination = [
        'total' => $resource->total(),
        'count' => $resource->count(),
        'per_page' => intval($resource->perPage()),
        'current_page' => $resource->currentPage(),
        'total_pages' => $resource->lastPage(),
    ];
    return ['data' => $collection, 'pagination' => $pagination];
}
