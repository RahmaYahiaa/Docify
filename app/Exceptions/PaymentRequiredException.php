<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class PaymentRequiredException extends Exception

{
    use ApiResponseTrait;
    protected $message = 'Payment required before booking this video consultation.';
    public function render($request): JsonResponse
    {
        return $this->paymentRequired($this->message);
    }
}
