<?php

namespace App\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface ProductServiceInterface
{
    public function getProducts(array $data);

    public function getProductByCode(array $data);

    public function addToFavourite(array $data);

    public function removeFromFavourites(array $data);

    public function getFavourites();

}
