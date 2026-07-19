<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class NotFoundException extends Exception
{
   use ApiResponseTrait;
    protected $message = 'The requested resource was not found.';
    public function render($request): JsonResponse
    {
        return $this->notFound($this->message);
    }
}
