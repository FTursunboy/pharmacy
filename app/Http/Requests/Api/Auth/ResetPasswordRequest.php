<?php

namespace App\Http\Requests\Api\Auth;

use App\Rules\LoginRule;
use App\Rules\PhoneRule;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ResetPasswordRequest extends FormRequest
{
    private $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

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
        $key = 'reset_password:' . $this->input('phone');

        $attempts = Cache::get($key, 0);

        if ($attempts >= 3) {
            throw new TooManyRequestsHttpException(null, 'Слишком много запросов. Пожалуйста, подождите.');
        }

        Cache::put($key, $attempts + 1, now()->addSeconds(60));

        return [
            'phone' => [
                'required',
                new LoginRule()
            ]
        ];
    }


    /**
     * Опционально, сбрасываем счетчик попыток после успешного запроса.
     */
    public function passedValidation()
    {
        $key = 'reset_password:' . $this->input('phone');
        $this->limiter->resetAttempts($key);
    }
}

