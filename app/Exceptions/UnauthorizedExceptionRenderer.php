<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UnauthorizedExceptionRenderer
{
    use ApiResponseTrait;

    public function handle(UnauthorizedException $e): JsonResponse
    {
        return $this->forbidden(__('messages.you_are_not_authorized_to_access_this_resource'));
    }
}
