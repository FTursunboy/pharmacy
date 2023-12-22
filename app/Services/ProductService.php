<?php

namespace App\Services;

use App\Models\ProductCategory;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\ProductCategoryServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProductService implements ProductServiceInterface
{


    public function getProd(array $data)
    {
        // TODO: Implement getProd() method.
    }
}
