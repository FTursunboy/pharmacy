<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'password' => 'as23d1gh' . md5($data['password']) . 'cvb8934er',
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
        $cleanedInputPhone = preg_replace('/[^0-9]/', '', $data['phone']);

        $user = $this->modelClass::where('phone', $cleanedInputPhone)->first();

        if (!$user) {
            $user = $this->modelClass::where('phone', $data['phone'])->first();
        }

        if (!$user && !str_starts_with($data['phone'], '+')) {
            $phone = '+' . $data['phone'];
            $user = $this->modelClass::where('phone', $phone)->first();
        }

        if (!$user || 'as23d1gh' . md5($data['password']) . 'cvb8934er' !== $user->password) {

            throw ValidationException::withMessages(['message' => __('auth.failed')]);
        }



        return $user;

    }


    public function codeVerification(array $data)
    {
        $cleanedInputPhone = preg_replace('/[^0-9]/', '', $data['phone']);

        $model = $this->modelClass::where('phone', $cleanedInputPhone)->first();

        if(!$model)
        {
            $model = $this->modelClass::where('phone', $data['phone'])->first();
        }



        if (!$model && !str_starts_with($data['phone'], '+')) {
            $phone = '+' . $data['phone'];

            $model = $this->modelClass::where('phone', $phone)->first();
        }


        if (!$model)
        {

            $formattedInputPhone = '+7 (' . substr($cleanedInputPhone, 1, 3) . ') ' . substr($cleanedInputPhone, 4, 3) . '-' . substr($cleanedInputPhone, 7, 2) . '-' . substr($cleanedInputPhone, 9, 2);

            $model = $this->modelClass::where('phone', $formattedInputPhone)->first();

        }


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
        $cleanedInputPhone = preg_replace('/[^0-9]/', '', $data['phone']);

        $model = $this->modelClass::where('phone', $cleanedInputPhone)->first();

        if(!$model)
        {
            $model = $this->modelClass::where('phone', $data['phone'])->first();
        }



        if (!$model && !str_starts_with($data['phone'], '+')) {
            $phone = '+' . $data['phone'];

            $model = $this->modelClass::where('phone', $phone)->first();
        }


        if (!$model)
        {

            $formattedInputPhone = '+7 (' . substr($cleanedInputPhone, 1, 3) . ') ' . substr($cleanedInputPhone, 4, 3) . '-' . substr($cleanedInputPhone, 7, 2) . '-' . substr($cleanedInputPhone, 9, 2);

            $model = $this->modelClass::where('phone', $formattedInputPhone)->first();

        }
        $code = "000000";
        $model->update(['code' => $code]);
    }

    public function setPassword(array $data)
    {
        $user = Auth::user();

        $user->update([
            'password' => 'as23d1gh' . md5($data['password']) . 'cvb8934er',
        ]);

        return $user;
    }
}
