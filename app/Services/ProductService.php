<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductProperty;
use App\Models\PromotionActionPageList;
use App\Services\Contracts\ProductServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


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
                    $product->price = $productProperty->price_stock !== null && $productProperty->price_stock != 0
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

    //Метод “/product“
    //
    //Принимает body параметром code товара
    //
    //Вернуть товар, выбирая тот, у которого shop_code одинаковый с shop_code юзера
    //
    //
    //
    //Товар должен содержать поля:
    //
    //id,
    //
    //code,
    //
    //name,
    //
    //manufacturer,
    //
    //description,
    //
    //image_name (брать из табл. product_images по code товара из табл. product)
    //
    //price (отдавать по той же логике, которая в списке товаров)
    //
    //old_price (по той же логике, которая в списке товаров)
    //
    //actions_list список из первых 5 элементов таблицы promotion_actions_page_list. У этих 5 элементов те же поля, что и у элементов, возвращаемых методом “/products” (name, code, image_name, price, old_price). Только price и oldPrice брать сразу тут (в табл. promotion_actions_page_list)

    public function getProductByCode(array $data)
    {
        $code = $data['code'];
        $userShopCode = Auth::user()->shop_code;

        $product = DB::table('products')
            ->join('product_properties as pp', 'pp.product_code', 'products.code')
            ->leftJoin('product_images as image', 'image.product_code', 'products.code')
            ->where([
                ['products.code', $code],
                ['pp.shop_code', $userShopCode]
            ])
            ->select('products.id', 'products.code', 'products.name', 'products.manufacturer', 'products.description', 'image.image_name', 'pp.price_stock', 'pp.price', 'pp.stock')
            ->first();


        if (!$product) {
            throw ValidationException::withMessages(['message' => 'The selected code is invalid.']);
        }


        if ($product->stock == 0) {
            $product->price = $product->price_stock !== null && $product->price_stock != 0
                ? $product->price_stock
                : $product->price;
            $product->old_price = null;
        } else {
            $promotion = PromotionActionPageList::where('product_code', $product->code)->first();

            if ($promotion) {
                $product->price = $promotion->price;
                $product->old_price = $promotion->old_price;
            }
            $product->price = $product->price;
            $product->old_price = null;
        }
        $action_list = DB::table('promotion_actions_page_list as pr')
            ->join('products as p', 'pr.product_code', 'p.code')
            ->leftJoin('product_images as image', 'image.product_code', 'pr.product_code')
            ->where([
                ['pr.status', 1]
            ])
            ->take(5)
            ->select('p.name', 'image.image_name', 'p.code', 'pr.price', 'pr.old_price')
            ->get();

        $product->action_list = $action_list;

        return $product;

    }

    public function addToFavourite(array $data)
    {
        $user = Auth::user();

        $user->favourites()->syncWithoutDetaching($data['product_code']);
    }

    public function removeFromFavourites(array $data)
    {
        $user = Auth::user();

        $user->favourites()->detach($data['product_code']);
    }


    public function getFavourites()
    {
        $products = DB::table('products')
            ->join('product_properties as pp', 'pp.product_code', 'products.code')
            ->leftJoin('product_images as image', 'image.product_code', 'products.code')
            ->join('bookmarked_products as bp', 'bp.product_code', '=', 'products.code')
            ->whereIn('bp.user_id', [Auth::id()])
            ->select('products.id', 'products.code', 'products.name', 'image.image_name', 'pp.price_stock', 'pp.price', 'pp.stock')
            ->get();

        if (!$products) {
            return [];
        }

        foreach ($products as $product) {

            if ($product->stock == 0) {
                $product->price = $product->price_stock !== null && $product->price_stock != 0
                    ? $product->price_stock
                    : $product->price;
                $product->old_price = null;
            } else {
                $promotion = PromotionActionPageList::where('product_code', $product->code)?->first();

                if ($promotion) {
                    $product->price = $promotion->price;
                    $product->old_price = $promotion->price_old;
                } else {
                    $product->price = $product->price;
                    $product->old_price = null;
                }
            }
        }

        return $products;
    }

}
