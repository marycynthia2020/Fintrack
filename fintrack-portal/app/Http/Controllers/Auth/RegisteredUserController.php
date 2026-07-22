<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use FinTrack\Core\Requests\RegisterUserRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
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

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
