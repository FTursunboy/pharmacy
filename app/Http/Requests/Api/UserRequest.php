<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'shop_code' => 'exists:shops,code',
            'name' => 'string|nullable',
            'email' => 'email|nullable|unique:users,email,' . auth()->id(),
            'city' => 'numeric|nullable|exists:cities,id'
        ];

    }
}
