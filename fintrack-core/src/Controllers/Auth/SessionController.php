<?php

namespace FinTrack\Core\Controllers\Auth;

use App\Http\Controllers\Controller;
use FinTrack\Core\Requests\LoginRequest;
use FinTrack\Core\Resources\LoginResource;
use FinTrack\Core\Models\User;
use FinTrack\Core\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use FinTrack\FinLib\Enums\Api;

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
            Api::Success->message()
        );
    }

    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, Api::Success->message());
    }

    public function refresh(Request $request)
    {
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();

        $token = $user->createToken('login-token', ['*'], now()->addHours(48));

        $user->load('organization');

        return $this->success(
            new LoginResource([
                'user' => $user,
                'token' => $token->plainTextToken,
            ]),
            Api::Success->message()
        );
    }
}
