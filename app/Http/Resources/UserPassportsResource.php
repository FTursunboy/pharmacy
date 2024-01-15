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
            'id' => $this->resource->id ?? null,
            'fio' => $this->resource->fio ?? null,
            'passport' => $this->resource->passport ?? null,
            'issue_place' => $this->resource->issue_place ?? null,
            'inn' => $this->resource->inn ?? null,
        ];
    }
}
