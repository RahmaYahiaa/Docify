<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class InsufficientDataExceptionRenderer
{
    use ApiResponseTrait;

public function handle(InsufficientDataException $e): JsonResponse
{
    return $this->badRequest(__('messages.insufficient_data'), null);
}
}