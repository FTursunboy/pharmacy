<?php

namespace App\Http\Resources;

use App\Models\BookMarkedProducts;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin \App\Models\PromotionActionPageList */
class ActionListInProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'name' => $this?->name,
            'code' => $this->code,
            'image_name' => $this->image_name,
            'price' => $this->resource->price,
            'old_price' => $this->resource->old_price,
            'isFavourite' => (bool)$this->isFavourite
        ];
    }
}
