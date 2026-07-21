<?php

namespace App\Services\Auth;

use App\Services\BaseApiService;

class AuthService extends BaseApiService
{
    public function login(array $credentials)
    {
        return $this->post('/fc-api/login', $credentials);
    }

    public function register(array $data)
    {
        return $this->post('/fc-api/register', $data);
    }

    public function logout()
    {
        return $this->post('/fc-api/logout');
    }
}