<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use libphonenumber\NumberParseException;

class PhoneNumberFormatExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(NumberParseException $e): JsonResponse
    {
        return $this->unprocessable($e->getMessage());
    }
}
