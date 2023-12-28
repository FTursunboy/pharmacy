<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    private $modelClass = User::class;

    public function register(array $data)
    {
        $user =  $this->modelClass::firstOrCreate([
            'phone' => $data['phone'],
            'name' => 'user',
            'password' => Hash::make($data['password']),
            'notify_offers' => $data['notify_offers'],
            'shop_code' => '5222'
        ]);

        $code = "000000";

        $user->update([
            'code' => $code
        ]);


    }

    public function login(array $data)
    {
        $user = $this->modelClass::where('phone', $data['phone'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(['message' => __('auth.failed')]);
        }

        return $user;

    }


    public function codeVerification(array $data)
    {
        $model = $this->modelClass::where('phone', $data['phone'])->first();

        if ($model) {
            if ($model->code == $data['code']) {
                $model->update([
                    'status' => User::STATUS_SUCCESS,
                    'code' => null
                ]);

                return $model;
            }
        }

        throw ValidationException::withMessages(['message' => __('auth.failed')]);
    }

    public function resendCode(array $data)
    {
        // TODO: Implement resendCode() method.
    }

    public function resetPassword(array $data)
    {
        $code = "000000";
        $this->modelClass::where('phone', $data['phone'])->update(['code' => $code]);
    }

    public function setPassword(array $data)
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }
}
