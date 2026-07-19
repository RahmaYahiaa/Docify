<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Admin\AdminDashboardResource;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return new AdminDashboardResource([]);
    }
}
