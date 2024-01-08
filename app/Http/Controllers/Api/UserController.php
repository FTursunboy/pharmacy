<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Requests\UserPassportsRequest;
use App\Http\Resources\UserPassportsResource;
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

    public  function update(UserRequest $request, UserServiceInterface $service) :JsonResponse
    {
        return $this->success(UserResource::make($service->update($request->validated())));
    }

    public function userDocs(UserPassportsRequest $request, UserServiceInterface $service) :JsonResponse
    {
        return $this->success(UserPassportsResource::make($service->addDocs($request->validated())));
    }

    public function getUserDocs(UserServiceInterface $service) :JsonResponse
    {
        return $this->success(UserPassportsResource::make($service->getUserDocs()));
    }
}
