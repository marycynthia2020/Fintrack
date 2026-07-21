<?php

namespace App\Services\Auth;

use App\Services\BaseApiService;

class AuthService extends BaseApiService
{
    public function login(array $credentials)
    {
        return $this->post('api/v1/fc/login', $credentials);
    }

    public function register(array $data)
    {
        return $this->post('api/v1/fc/register', $data);
    }

    public function logout()
    {
        return $this->post('api/v1/fc/logout');
    }
};