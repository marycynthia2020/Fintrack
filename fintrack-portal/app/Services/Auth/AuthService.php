<?php

namespace App\Services\Auth;

use App\Services\BaseApiService;
use Exception;
use FinTrack\Core\Models\User;
use FinTrack\Core\Resources\LoginResource;
use FinTrack\Core\Traits\ApiResponse;
use FinTrack\FinLib\Enums\Api;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseApiService
{
    use ApiResponse;
    public function login(array $credentials, $request): LoginResource|string
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return ('Invalid credentials.');
        }

        $user->tokens()->delete();

        $token = $user->createToken('login-token', ['*'], now()->addHours(48));

        $user->load('organization');

       return new LoginResource([
                'user' => $user,
                'token' => $token->plainTextToken,
            ]);

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
