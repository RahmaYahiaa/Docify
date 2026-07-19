<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Http\JsonResponse;

class InvalidFormatExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(InvalidFormatException $e): JsonResponse
    {
        return $this->unprocessable(__('messages.invalid_date_format'));
    }
}
