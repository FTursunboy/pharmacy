<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'phone' => [
                'string',
                'unique:users,phone'
            ],
            'password' => 'string|min:6',
            'notify_offers' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => 'Аккаунт уже существут. Выполните вход',
        ];
    }
}
