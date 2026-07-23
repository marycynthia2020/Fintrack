<x-guest-layout>
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
       <div class="mb-6 flex flex-col items-center">
                <h2 class="text-2xl font-bold text-slate-900 leading-tight">Welcome back</h2>
                <p class="mt-1.5 text-sm text-slate-500">Sign in to your account</p>
        </div>
        <!-- Email Address -->
        <div>
            <label class="ft-label" for="email">Email address <span class="text-red-500">*</span></label>
            <input id="email" class="ft-input" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label class="ft-label" for="password">Password <span class="text-red-500">*</span></label>
            <input id="password" class="ft-input" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="ft-auth-row">
            <label><input id="remember_me" type="checkbox" name="remember"> <span>Remember me</span></label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>
        <button class="ft-button" type="submit">Sign in</button>
    </form>
    <p class="ft-auth-footer">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
</x-guest-layout>
