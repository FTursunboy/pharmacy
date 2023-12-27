<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\ShopResource;
use App\Services\Contracts\ShopServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    use ApiResponse;

    public function index(ShopServiceInterface $service) :JsonResponse
    {
        return $this->success(ShopResource::collection($service->index()));
    }

    public function getCities(ShopServiceInterface $service) :JsonResponse
    {
        return $this->success(CityResource::collection($service->getCities()));
    }
}
