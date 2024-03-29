<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\SetPasswordRequest;
use App\Http\Requests\Api\Auth\VerifyRequest;
use App\Http\Resources\AuthResource;
use App\Services\Contracts\AuthServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request, AuthServiceInterface $service) :JsonResponse
    {
        return $this->success($service->register($request->validated()));
    }

    public function login(LoginRequest $request, AuthServiceInterface $service) :JsonResponse
    {
        $user = $service->login($request->validated());
        $token = $user->createToken('api token')->plainTextToken;
        return response()->json([
            'user' => AuthResource::make($user),
            'token' => $token,
        ]);
    }

    public function confirm(VerifyRequest $request, AuthServiceInterface $service) :JsonResponse
    {
        $user = $service->codeVerification($request->validated());
        $apiToken = $user->createToken('login token');

        return response()->json([
            'user' => AuthResource::make($user),
            'token' => $apiToken->plainTextToken,
        ]);
    }


    public function resetPassword(ResetPasswordRequest $request, AuthServiceInterface $service) :JsonResponse
    {
        return $this->success($service->resetPassword($request->validated()));
    }

    public function setPassword(SetPasswordRequest $request, AuthServiceInterface $service) :JsonResponse
    {
        $user = $service->setPassword($request->validated());
        $token = $user->createToken('api token')->plainTextToken;
        return response()->json([
            'user' => AuthResource::make($user),
            'token' => $token,
        ]);

    }


}
