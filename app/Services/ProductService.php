<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductProperty;
use App\Models\PromotionActionPageList;
use App\Services\Contracts\ProductServiceInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class ProductService implements ProductServiceInterface
{

    const ON_PAGE = 20;

    public function getProducts(array $data) :LengthAwarePaginator
    {
        $categoryCode = $data['categoryCode'] ?? null;
        $search = $data['search'] ?? null;
        $sort = $data['sort'] ?? 'price';
        $order = $data['order'] ?? 'asc';

        $products = Product::with(['property', 'image', 'promotionActionPageList']);

        $products->when(!$categoryCode && $search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('name_search', 'like', '%' . $search . '%');
        });

        $products->when($categoryCode, function ($query) use ($categoryCode, $search) {
            $query->join('product_properties as pp', 'pp.product_code', 'products.code')
                ->leftJoin('product_images as image', 'image.product_code', 'products.code')
                ->where('products.category_id', $categoryCode)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('name_search', 'like', '%' . $search . '%');
                })
                ->select('products.id', 'products.code', 'products.name', 'products.manufacturer', 'products.description', 'image.image_name', 'pp.price_stock', 'pp.price', 'pp.stock', 'products.name_search');
        });

        $products->when(!$categoryCode, function ($query) {
            $query->join('product_properties as pp', 'pp.product_code', 'products.code')
                ->join('product_images as image', 'image.product_code', 'products.code')
                ->select('products.id', 'products.code', 'products.name', 'products.manufacturer', 'products.description', 'image.image_name', 'pp.price_stock', 'pp.price', 'pp.stock', 'products.name_search')
                ->distinct('pp.product_code');
        });

        $products->when($sort && $order, function ($query) use ($sort, $order) {
            $query->orderBy($sort, $order);
        });

        $products->each(function ($product) {
            if ($product->stock == 0) {
                $product->price = $product->price_stock !== null && $product->price_stock != 0
                    ? $product->price_stock
                    : $product->price;
                $product->old_price = null;
            } else {
                $promotion = PromotionActionPageList::where('product_code', $product->code)->first();

                if ($promotion) {
                    $product->price = $promotion->price;
                    $product->old_price = $promotion->price_old;
                } else {
                    $product->price = $product->price;
                    $product->old_price = null;
                }
            }
        });


        return $products->paginate(self::ON_PAGE);
    }


    public function getProductByCode(array $data)
    {
        $code = $data['code'];
        $userShopCode = Auth::user()->shop_code;

        $product = DB::table('products')
            ->join('product_properties as pp', 'pp.product_code', 'products.code')
            ->leftJoin('product_images as image', 'image.product_code', 'products.code')
            ->where([
                ['products.code', $code]
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

    public function actionList() :LengthAwarePaginator
    {
        return PromotionActionPageList::with('product')
            ->where('shop_code', Auth::user()->shop_code)
            ->paginate(10);

    }


}
