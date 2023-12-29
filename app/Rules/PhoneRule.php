<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PhoneRule implements Rule
{

    public function passes($attribute, $value)
    {
        $cleanedPhones = DB::table('users')->pluck('phone')->map(function ($phone) {
            return preg_replace('/[^0-9]/', '', $phone);
        });

        $cleanedInputPhone = preg_replace('/[^0-9]/', '', $value);

        return !$cleanedPhones->contains($cleanedInputPhone);
    }


    public function message()
    {
        return 'Аккаунт уже существут. Выполните вход';
    }
}
