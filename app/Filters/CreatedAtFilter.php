<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait CreatedAtFilter
{
    #[Scope]
    protected function createdFrom(Builder $query, $date): Builder
    {
        return $query->whereDate('created_at', '>=', Carbon::parse($date));
    }

    #[Scope]
    protected function createdTo(Builder $query, $date): Builder
    {
        return $query->whereDate('created_at', '<=', Carbon::parse($date));
    }
}
