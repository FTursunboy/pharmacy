<?php

namespace App\Services;

use App\Models\Shop;
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

        $city = Shop::query()
            ->where('city_name', $data['city'])->first();

        $user = User::find(Auth::id());

        if ($city) {
            $user->shop_code = $city->code;

        }
        if($data['name']) {
            $user->name = $data['name'];
        }
        if ($data['email']) {
            $user->email = $data['email'];
        }

        $user->save();

        return $user;
    }
}
