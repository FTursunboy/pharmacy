<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPassportsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fio' => 'nullable|string',
            'passport' => 'nullable|string',
            'issue_place' => 'nullable|string',
            'inn' => 'nullable|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
