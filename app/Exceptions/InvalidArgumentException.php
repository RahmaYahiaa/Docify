<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class InvalidArgumentException extends Exception
{
    use ApiResponseTrait;
    protected $message = 'Invalid argument provided.';
    public function render($request): JsonResponse
    {
        return $this->badRequest($this->message);
    }
}
