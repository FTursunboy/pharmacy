<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    public function register(array $data);

    public function login(array $data);

    public function codeVerification(array $data);

    public function resendCode(array $data);

    public function resetPassword(array $data);

    public function setPassword(array $data);
}
