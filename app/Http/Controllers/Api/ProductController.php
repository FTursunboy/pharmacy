<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FavouriteRequest;
use App\Http\Requests\Api\ProductCodeRequest;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\ActionListResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductByCodeResource;
use App\Http\Resources\ProductResource;
use App\Services\Contracts\ProductCategoryServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function getProducts(ProductRequest $request, ProductServiceInterface $service): JsonResponse
    {
        return $this->paginate(ProductResource::collection($service->getProducts($request->validated())));
    }

    public function productByCode(ProductCodeRequest $request, ProductServiceInterface $service): JsonResponse
    {
        return $this->success(ProductByCodeResource::make($service->getProductByCode($request->validated())));
    }

    public function addToFavourite(FavouriteRequest $request, ProductServiceInterface $service): JsonResponse
    {
        return $this->success($service->addToFavourite($request->validated()));
    }

    public function removeFromFavourites(FavouriteRequest $request, ProductServiceInterface $service) :JsonResponse
    {
        return $this->success($service->removeFromFavourites($request->validated()));
    }

    public function getFavourites(ProductServiceInterface $service) :JsonResponse
    {
        return $this->success(ProductResource::collection($service->getFavourites()));
    }

    public function actionList(ProductServiceInterface $service) :JsonResponse
    {
      return $this->success(ActionListResource::collection($service->actionList()));
    }

    public function manufacturer(ProductServiceInterface $service, string $productCode = null) :JsonResponse
    {
        return $this->success($service->manufacturers($productCode));
    }
}
