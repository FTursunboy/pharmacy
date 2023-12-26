<?php

namespace App\Services\Contracts;

use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface BannerServiceInterface
{
    public function index() :Collection;


}
