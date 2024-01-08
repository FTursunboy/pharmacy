<?php

namespace App\Http\Resources;

use App\Models\BookMarkedProducts;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin \App\Models\PromotionActionPageList */
class ActionListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = Product::where('code', $this->product_code)->first();

        $image = ProductImage::where('product_code', $this->product_code)->first()?->image_name;
        return [
            'name' => $product?->name,
            'code' => $this->resource->product_code,
            'image_name' => $image,
            'price' => $this->resource->price,
            'old_price' => $this->resource->old_price,
            'isFavourite' => BookMarkedProducts::query()->where([
                ['product_code', $this->resource->product_code],
                ['user_id', Auth::id()]
            ])->exists()
        ];
    }
}
