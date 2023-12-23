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

    public function getCities()
    {
        return Shop::query()
            ->select('city_name')
            ->distinct()
            ->get();
    }
}
