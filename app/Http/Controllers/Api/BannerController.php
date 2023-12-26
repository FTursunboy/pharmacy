<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductCategoryRequest;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Services\Contracts\BannerServiceInterface;
use App\Services\Contracts\ProductCategoryServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use ApiResponse;

    public function getBanners(BannerServiceInterface $service) :JsonResponse
    {
        return $this->success(BannerResource::collection($service->index()));
    }


}
