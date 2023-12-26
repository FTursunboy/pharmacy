<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\ProductCategory;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\BannerServiceInterface;
use App\Services\Contracts\ProductCategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class BannerService implements BannerServiceInterface
{

    public function index(): Collection
    {
       return Banner::get();
    }
}
