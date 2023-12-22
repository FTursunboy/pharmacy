<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->name,
            'code' => $this->resource->code,
            'image_name' => $this->resource->image_name,
            'price' => $this->resource->price,
            'old_price' => $this->resource->old_price,
        ];
    }
}
