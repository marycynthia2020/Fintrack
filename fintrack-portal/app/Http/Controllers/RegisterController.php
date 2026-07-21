<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Show the registration form.
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'min:3', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'password'     => ['required', 'string', 'min:4'],
            'organization' => ['nullable', 'string', 'max:255'],
        ]);

        $response = $this->authService->register($data);

        if (! $response->successful()) {

            $errors = $response->json('errors');

            if ($errors) {
                return back()
                    ->withErrors($errors)
                    ->withInput();
            }

            return back()
                ->withErrors([
                    'email' => $response->json('message') ?? 'Registration failed.',
                ])
                ->withInput();
        }

        return redirect()
            ->route('login')
            ->with('status', 'Account created successfully. Please log in.');
    }
}