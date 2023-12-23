<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService implements UserServiceInterface
{

    public function profile()
    {
        $user = Auth::user();

        return $user;
    }

    public function update(array $data)
    {
        // TODO: Implement update() method.
    }
}
