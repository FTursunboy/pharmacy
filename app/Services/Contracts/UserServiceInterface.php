<?php

namespace App\Services\Contracts;

interface UserServiceInterface
{
    public function profile();

    public function update(array $data);

}
