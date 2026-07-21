@extends('layouts.auth')

@section('content')

<x-auth-card title="Welcome back!" subtitle="Sign in to your account">
    <x-status />

    <form action="{{ route('login.store') }}" method="POST">
        @csrf

        <x-input 
            label="Email" 
            name="email" 
            type="email" 
            placeholder="email@example.com" 
            required 
        />

        <x-input 
            label="Password" 
            name="password" 
            type="password" 
            placeholder="Enter your password" 
            required 
        />

        {{-- <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4.5 w-4.5 text-blue-600 dark:text-blue-500 focus:ring-blue-500 border-gray-300 dark:border-zinc-700 rounded bg-white dark:bg-zinc-900"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-zinc-300 cursor-pointer select-none">
                    Remember me
                </label>
            </div>
        </div> --}}

        <div class="mt-6">
            <x-button variant="primary" type="submit">
            Sign In
        </x-button>
        </div>
    </form>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200 dark:border-zinc-800"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-white dark:bg-zinc-950 px-2 text-gray-500 dark:text-zinc-400">
                or continue with
            </span>
        </div>
    </div>

    <button type="button" class="w-full inline-flex justify-center items-center px-4 py-3 border border-gray-350 dark:border-zinc-800 rounded-lg text-sm font-semibold text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 cursor-pointer shadow-sm">
        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
        </svg>
        Google
    </button>

    <div class="mt-6 text-center text-sm text-gray-500 dark:text-zinc-400">
        Don't have an account? 
        <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
            Sign up
        </a>
    </div>
</x-auth-card>
@endsection