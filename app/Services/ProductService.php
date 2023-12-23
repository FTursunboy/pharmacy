<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductProperty;
use App\Models\PromotionActionPageList;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Support\Facades\Auth;


class ProductService implements ProductServiceInterface
{

    const ON_PAGE = 20;


    public function getProducts(array $data)
    {
        $categoryCode = $data['categoryCode'];
        $userShopCode = Auth::user()->shop_code;

        $products = Product::where('category_id', $categoryCode)
            ->paginate(self::ON_PAGE);

        foreach ($products as $product) {
            $productImage = ProductImage::where('product_code', $product->code)->first();
            $product->image_name = $productImage ? $productImage->image_name : null;

            $productProperty = ProductProperty::where('product_code', $product->code)
                ->where('shop_code', $userShopCode)
                ->first();

            if ($productProperty) {
                if ($productProperty->stock == 0) {
                    $product->price = $productProperty->price_stock != null && $productProperty->price_stock != 0
                        ? $productProperty->price_stock
                        : $productProperty->price;
                    $product->old_price = null;
                } else {
                    $promotion = PromotionActionPageList::where('product_code', $product->code)->first();

                    if ($promotion) {
                        $product->price = $promotion->price;
                        $product->old_price = $promotion->price_old;
                    } else {
                        $product->price = $productProperty->price;
                        $product->old_price = null;
                    }
                }
            }
        }

        return $products;
    }



}
