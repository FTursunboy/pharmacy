<?php

namespace App\Services;

use App\Models\ProductCategory;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\ProductCategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProductCategoryService implements ProductCategoryServiceInterface
{

    public function getCategories(array $data) :Collection
    {
        return ProductCategory::query()
            ->where([
                ['parent_code', $data['categoryCode']],
                ['active', 1]
            ])
            ->get();
    }

}
