<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use FinTrack\Core\Models\User;
use FinTrack\Core\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    use ApiResponse;

    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return $this->unauthorized('Invalid credentials.');
        }

        $user->tokens()->delete();

        $token = $user->createToken('login-token', ['*'], now()->addHours(48));

        $user->load('organization');

        return $this->success(
            new LoginResource([
                'user' => $user,
                'token' => $token->plainTextToken,
            ]),
            'Login successful.'
        );
    }

    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully.');
    }
}
