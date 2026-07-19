<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class AccessDeniedHttpExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(string $message): JsonResponse
    {
        return $this->forbidden($message);
    }
}
