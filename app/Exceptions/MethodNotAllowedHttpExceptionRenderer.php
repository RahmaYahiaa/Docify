<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class MethodNotAllowedHttpExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(MethodNotAllowedHttpException $e): JsonResponse
    {
        return $this->methodNotAllowed(__('messages.method_not_allowed'));
    }
}
