<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class ForbiddenException extends Exception
{
    use ApiResponseTrait;

    protected $message = 'You are not authorized to perform this action.';

    public function render($request): JsonResponse
    {
        return $this->forbidden($this->getMessage());
    }
}
