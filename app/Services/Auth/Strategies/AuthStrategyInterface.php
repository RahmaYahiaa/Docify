<?php

namespace App\Services\Auth\Strategies;

use Illuminate\Http\Request;

interface AuthStrategyInterface
{
    public function authenticate(Request $request);
}
