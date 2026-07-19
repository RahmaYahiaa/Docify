<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class NameFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->searchByName($value);
    }
}
