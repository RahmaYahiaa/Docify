<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;

class UniqueConstraintViolationExceptionRendor
{
    use ApiResponseTrait;

    public function handle(UniqueConstraintViolationException $e): JsonResponse
    {
        return $this->forbidden(__('messages.uniqe_property_order'));
    }
}
