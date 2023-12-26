<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\Contracts\ProductCategoryServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    use ApiResponse;

    public function getCategories(ProductCategoryRequest $request, ProductCategoryServiceInterface $service) :JsonResponse
    {
        return $this->success(CategoryResource::collection($service->getCategories($request->validated())));
    }

    public function activeCategories(ProductCategoryServiceInterface $service)
    {
        return $this->success(CategoryResource::collection($service->activeCategories()));
    }
}
