<?php

namespace App\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface ProductServiceInterface
{
    public function getProd(array $data);

}
