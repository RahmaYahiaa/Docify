<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class InsufficientDataException extends Exception
{
    use ApiResponseTrait;

   
    public function render($request): JsonResponse
    {
        return $this->badRequest(__('messages.insufficient_data'));
    }
}