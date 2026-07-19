<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class UnsupportedMeasureTypeExceptionRenderer
{
    use ApiResponseTrait;


    public function handle(UnsupportedMeasureTypeException $e): JsonResponse
    {
    
        return $this->badRequest(__('messages.unsupported_measure_type'));
    }
}