<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function paginate($result = 'Успешно', $code = 200): JsonResponse
    {
        if (is_string($result))
            return $this->success($result, $code);
        return response()->json(['result' => paginatedResponse($result)], $code);
    }

    public function success($result = 'Успешно', $code = 200): JsonResponse
    {
        return response()->json(['result' => $result ?? 'Успешно', 'errors' => null], $code);
    }

    public function paginateChat($result = 'Успешно', $code = 200): JsonResponse
    {
        if (is_string($result))
            return $this->success($result, $code);

        return response()->json(['result' => paginatedResponseChat($result)], $code);
    }

    public function notAccess($message = 'У вас нет доступа!', $code = 405): JsonResponse
    {
        return $this->error(['message' => $message], $code);
    }

    public function error($error = 'Что-то пошло не так', $code = 400): JsonResponse
    {
        return response()->json(['result' => null, 'errors' => $error ?? 'Что-то пошло не так'], $code);
    }

    public function blocked($message = 'Ваш аккаунт заблокирован', $code = 403): JsonResponse
    {
        return $this->error(['message' => $message], $code);
    }

    public function noChatAccess($message = 'У вас нет доступа!', $code = 200): JsonResponse
    {
        return $this->error(['message' => $message], $code);
    }
}
