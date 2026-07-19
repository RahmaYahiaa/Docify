<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ValidationExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(ValidationException $e): JsonResponse
    {
        return $this->unprocessable($e->validator->errors());
    }
}
