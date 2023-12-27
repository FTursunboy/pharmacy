<?php

namespace App\Http\Resources;

use App\Models\BookMarkedProducts;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductByCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'manufacturer' => $this->resource->manufacturer,
            'description' => $this->resource->description,
            'image_name' => $this->resource->image_name,
            'price' => $this->resource->price,
            'old_price' => $this->old_price,
            'isFavourite' => BookMarkedProducts::query()->where('product_code', $this->resource->code)->exists(),
            'action_list' => $this->action_list
        ];
    }
}
