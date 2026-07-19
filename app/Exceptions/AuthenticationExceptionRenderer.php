<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthenticationExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(string $message): JsonResponse
    {
        return $this->unauthorized($message);
    }
}
