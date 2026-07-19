<?php

namespace App\Services\Auth;

use App\Services\Auth\Strategies\AuthStrategyInterface;
use Illuminate\Http\Request;

class AuthService
{
    public function __construct(
        protected AuthStrategyInterface $strategy
    ) {}

    public function authenticate(Request $request)
    {
        return $this->strategy->authenticate($request);
    }
}
