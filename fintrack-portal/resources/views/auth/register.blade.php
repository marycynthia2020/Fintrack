@extends('layouts.auth')

@section('content')


<x-auth-card title="Create Your Account" subtitle="Let's set up your account">
    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <x-input 
            label="Name" 
            name="name" 
            type="text" 
            placeholder="Chinemerem Ugbaja" 
            required 
        />

        <x-input 
            label="Email" 
            name="email" 
            type="email" 
            placeholder="email@example.com" 
            required 
        />

        <div>
            <x-input 
                label="Password" 
                name="password" 
                type="password" 
                placeholder="Create a secure password" 
                required 
            />
            {{-- <p class="text-xs text-slate-500 -mt-2 mb-4 font-normal">
                Must be at least 4 characters, containing both uppercase and lowercase letters and numbers.
            </p> --}}
        </div>

        <x-input 
            label="Organization name" 
            name="organization" 
            type="text" 
            placeholder="e.g. Chi Enterprise" 
        />

        <div class="mt-6">
            <x-button variant="primary" type="submit">
                Create Account
            </x-button>
        </div>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500">
        Already have an account? 
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
            Sign In
        </a>
    </div>
</x-auth-card>
@endsection
