<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserPassports */
class UserPassportsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id ?? "",
            'fio' => $this->resource->fio ?? "",
            'passport' => $this->resource->passport ?? "",
            'issue_place' => $this->resource->issue_place ?? "",
            'inn' => $this->resource->inn ?? "",
        ];
    }
}
