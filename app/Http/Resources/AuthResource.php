<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'notify_offers' => $this->resource->notify_offers,
            'created_at' => $this->resource->created_at,
        ];
    }

}
