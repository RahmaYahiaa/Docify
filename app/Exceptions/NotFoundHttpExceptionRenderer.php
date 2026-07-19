<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundHttpExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(NotFoundHttpException $e): JsonResponse
    {
        return $this->notFound(__('messages.not_found'));
    }
}
