<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $response = $this->authService->login($credentials, $request);

        dd($response);

        if (! $response->successful()) {

            return back()
                ->withErrors([
                    'email' => $response->json('message') ?? 'Invalid credentials.',
                ])
                ->withInput();
        }

        $data = $response->json('data');

        session([
            'api_token' => $data['token'],
            'user' => $data['user'],
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Login successful');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
