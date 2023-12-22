<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ProductCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\Contracts\ProductCategoryServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function getProducts(ProductCategoryRequest $request, ProductCategoryServiceInterface $service) :JsonResponse
    {
        return $this->success(CategoryResource::collection($service->getCategories($request->validated())));
    }
}
