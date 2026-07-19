<?php

namespace App\Http\Controllers\API\V1\Payment;

use App\Actions\Payment\CheckPaymentStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Payment\CheckPaymentStatusRequest;
use App\Http\Resources\API\V1\Payment\PaymentStatusResource;
use Illuminate\Http\JsonResponse;

class PaymentStatusController extends Controller
{
    public function __invoke(CheckPaymentStatusRequest $request, CheckPaymentStatusAction $action): JsonResponse
    {
        $result = $action->execute(cacheKey: $request->validated()['cache_key']);

        return $this->ok($result['message'], new PaymentStatusResource($result));
    }
}
