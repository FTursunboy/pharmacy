<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $shop = Shop::query()->where('code', $this->shop_code)->first();

        $city = City::where('code', $shop?->city_code)?->first()?->name;

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'city' => $city,
            'shop' => ShopResource::make(Shop::where('code', $this->shop_code)->first()),
        ];


    }
}
