<?php

namespace App\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface ProductServiceInterface
{
    public function getProducts(array $data);

}
