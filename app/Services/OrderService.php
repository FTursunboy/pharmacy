<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Order;
use App\Models\ProductCategory;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\BannerServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductCategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class OrderService implements OrderServiceInterface
{

    public function index()
    {
       return Order::with(['orderProducts.product'])
        ->where('user_id', \Auth::id())
        ->paginate(10);

    }
}
