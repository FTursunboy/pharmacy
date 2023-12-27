<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductProperty;
use App\Models\PromotionActionPageList;
use App\Models\Shop;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\ShopServiceInterface;
use Illuminate\Support\Facades\Auth;


class ShopService implements ShopServiceInterface
{

    public function index() {
        return Shop::query()
                ->where('shop_functions_enabled', 1)
            ->get();
    }

    public function getCities()
    {
        return Shop::query()
            ->selectRaw('MIN(id) as id, city_name')
            ->groupBy('city_name')
            ->orderBy('id')
            ->get();
    }

}
