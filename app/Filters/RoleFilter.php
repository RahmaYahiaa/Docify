<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class RoleFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {

        return $query->whereHas('roles', fn ($q) => $q->where('id', $value));
    }
}
