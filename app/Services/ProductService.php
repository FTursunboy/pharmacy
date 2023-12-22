<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductProperty;
use App\Models\PromotionActionPageList;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\ProductCategoryServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProductService implements ProductServiceInterface
{

    const ON_PAGE = 20;

    //price
    //
    //oldPrice
    //
    //Логика:
    //
    //Смотрим в таблицу product_properties
    //
    //Выбираем только те товары, у которых shop_code одинаковый с shop_code юзера
    //
    //Если stock == 0 :
    //
    //Поле price: отдаём old_price. Если old_price == null || old_price == 0, то берём значение price,
    //
    //Поле oldPrice: null
    //
    //Если stock > 0 :
    //
    //Идём в таблицу promotion_actions_page_list
    //
    //Поле price: значение price,
    //
    //Поле oldPrice: отдаём old_price
    //
    //Если товара в этой таблице не оказалось, то брать price ответа = price из product_properties, oldPrice null

    public function getProducts(array $data)
    {
        $categoryCode = $data['categoryCode'];
        $userShopCode = Auth::user()->shop_code;

        $products = Product::query()
            ->where([
                ['category_id', $categoryCode],
            ])
            ->paginate(self::ON_PAGE);

        foreach ($products as $product) {
            $product->image_name = ProductImage::where('product_code', $product->code)->value('image_name');


            $productProperty = ProductProperty::where('product_code', $product->code)
                ->where('shop_code', $userShopCode)
                ->first();


            if ($productProperty) {
                if ($productProperty->stock == 0) {
                    $product->price = $productProperty->old_price ?? $productProperty->price;
                    $product->stock = $productProperty->stock;
                    $product->oldPrice = null;
                } else {
                    $promotion = PromotionActionPageList::where('product_code', $product->code)
                        ->first();

                    if ($promotion) {
                        $product->price = $promotion->price;
                        $product->old_price = $promotion->old_price;
                    }
                    else {
                        $product->price = $productProperty->old_price ?? $productProperty->price;
                        $product->stock = $productProperty->stock;
                        $product->oldPrice = null;
                    }
                }
            }
        }


        return $products;
    }


}