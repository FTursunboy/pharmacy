<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\PromotionActionPageList;
use App\Models\Shop;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderModelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'remote_code' => $this->remote_code,
            'status' => $this->status,
            'wait' => $this->wait,
            'created_at' => $this->created_at,
            'shop' => ShopResource::make($this->shop),
            'products' => ProductOrderResource::collection((new ProductService())->getProductForOrder($this->productCodes())),
        ];
    }
}
