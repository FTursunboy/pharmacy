<?php

namespace App\Services;

use App\Models\City;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserPassports;
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

    public function addDocs(array $data) :UserPassports
    {
        $user_data = UserPassports::where('user_id', Auth::id())->firstOrNew();

        if (isset($data['passport'])) {
            $user_data->passport = $data['passport'];
        }
        if (isset($data['issue_place'])) {
            $user_data->issue_place = $data['issue_place'];
        }
        if (isset($data['inn'])) {
            $user_data->inn = $data['inn'];
        }
        if (isset($data['fio'])) {
            $user_data->fio = $data['fio'];
        }
        $user_data->phone = Auth::user()->phone;
        $user_data->email = Auth::user()->email;
        $user_data->user_id = Auth::id();

        $user_data->save();

        return $user_data;
    }

    public function getUserDocs() :UserPassports|null
    {
        return UserPassports::where('user_id', Auth::id())->first();
    }
}
