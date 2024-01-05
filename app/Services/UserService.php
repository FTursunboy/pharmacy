<?php

namespace App\Services;

use App\Models\City;
use App\Models\Shop;
use App\Models\User;
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
        $user = User::find(Auth::id());

        if (isset($data['shop_code']) && $data['shop_code']) {
            $user->shop_code = $data['shop_code'];
        }


        if (isset($data['city']) && $data['city']) {
            $code = City::find($data['city'])->code;
            $shop_code = Shop::where('city_code', $code)->first()->code;
            $user->shop_code = $shop_code;
        }

        if(isset($data['name']) && $data['name']) {
            $user->name = $data['name'];
        }
        if (isset($data['email']) && $data['email']) {
            $user->email = $data['email'];
        }

        $user->save();

        return $user;
    }
}
