<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class QueryExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(QueryException $e): JsonResponse
    {
        return $this->unprocessable(__('messages.not_found'));
    }
}
