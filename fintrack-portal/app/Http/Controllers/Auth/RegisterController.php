<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use FinTrack\Core\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use ApiResponse;

    /**
     * Handle the user registration request.
     */
    public function store(RegisterUserRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            $orgName = $request->input('organization') ?: $request->input('name');

            $organization = Organization::create([
                'name' => $orgName,
            ]);

            return User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'organization_id' => $organization->id,
            ]);
        });

        $user->load('organization');

        return $this->created(new UserResource($user), 'User registered successfully.');
    }
}
