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
            'newsletter_confirmation' => $this->resource->newsletter_confirmation,
            'created_at' => $this->resource->created_at,
        ];
    }

}
