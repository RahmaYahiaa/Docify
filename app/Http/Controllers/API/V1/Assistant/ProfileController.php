<?php

namespace App\Http\Controllers\API\V1\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Assistant\ProfileResource;



class ProfileController extends Controller
{

    public function info()
    {
        $user = auth()->user()->load('doctor');

        return $this->ok( data: new ProfileResource($user));
    }
}