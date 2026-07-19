<?php

namespace App\Http\Resources\API\V1\Payment\PaymentMethod;

use App\Http\Resources\API\V1\Payment\PaymentMethod\PaymentMethodResource;
use App\Http\Resources\BasePaginationResource;

class PaymentMethodCollection extends BasePaginationResource
{
    public $collects = PaymentMethodResource::class;
}