<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\Exceptions\InvalidFilterQuery;

class InvalidFilterQueryExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(InvalidFilterQuery $e): JsonResponse
    {
        return $this->unprocessable($e->getMessage());
    }
}
