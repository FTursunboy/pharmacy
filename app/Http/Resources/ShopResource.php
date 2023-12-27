<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
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
            'phone' => $this->resource->phone,
            'address' => $this->resource->address,
            'longitude' => $this->resource->longitude,
            'latitude' => $this->resource->latitude,
            'workhours_human' => $this->resource->workhours_human
        ];
    }
}
