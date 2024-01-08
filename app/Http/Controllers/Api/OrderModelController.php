<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderModelResource;


use App\Services\Contracts\OrderServiceInterface;
use App\Traits\ApiResponse;


class OrderModelController extends Controller
{
    use ApiResponse;
    public function index(OrderServiceInterface $service)
    {
        return $this->paginate(OrderModelResource::collection($service->index()));
    }

}
