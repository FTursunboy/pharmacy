<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPassportsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fio' => 'required|string',
            'passport' => 'required|string',
            'issue_place' => 'required|string',
            'inn' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
