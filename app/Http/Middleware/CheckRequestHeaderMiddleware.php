<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckRequestHeaderMiddleware
{
    use ApiResponseTrait;

    public function handle(Request $request, Closure $next)
    {
        $requestPrivateKey = $request->header('private-key');
        $privateKey = config('app.api_private_key');
        $latestPrivateKey = config('app.latest_api_private_key');

        if (! in_array($request->header('Accept-language'), languages())) {
            return $this->unprocessable(message: 'we can\'t detect application language');
        }

        if (empty($request->header('platform')) || $request->header('platform') == 'null') {
            return $this->unprocessable(message: 'we can\'t detect application platform');
        }

        if (empty($requestPrivateKey) || empty($privateKey) || empty($latestPrivateKey)) {
            return $this->unprocessable(message: 'Un-Authenticated');
        }

        if (($requestPrivateKey === $latestPrivateKey) || ($requestPrivateKey === $privateKey)) {
            return $next($request);
        }

        return $this->unprocessable(message: 'Un-Authenticated');
    }
}
