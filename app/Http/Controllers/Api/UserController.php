<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Contracts\UserServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function profile(UserServiceInterface $service) :JsonResponse
    {
        return $this->success(UserResource::make($service->profile()));
    }

    public  function update() :JsonResponse
    {

    }
}
