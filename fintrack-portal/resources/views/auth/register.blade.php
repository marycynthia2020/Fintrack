<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-6 flex flex-col items-center">
         
                <h2 class="text-2xl font-bold text-slate-900 leading-tight">Create your account</h2>
                <p class="mt-1.5 text-sm text-slate-500">Fill your details to get started</p>
        </div>
        <!-- Name -->
        <div>
            <label class="ft-label" for="name">Name <span class="text-red-500">*</span></label>
            <input id="name" class="ft-input" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-3">
            <label class="ft-label" for="email">Email address <span class="text-red-500">*</span></label>
            <input id="email" class="ft-input" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

         <!-- Business Name -->
        <div class="mt-3">
            <label class="ft-label" for="organization">Business name</label>
            <input id="organization" class="ft-input" type="password" name="organization" required autocomplete="organization" placeholder="John Enterprise" />

            <x-input-error :messages="$errors->get('organization')" class="mt-2" />
        </div>
        <!-- Password -->
        <div class="mt-3">
            <label class="ft-label" for="password">Password <span class="text-red-500">*</span></label>
            <input id="password" class="ft-input" type="password" name="password" required autocomplete="new-password" placeholder="*******" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
           

        <!-- Confirm Password -->
        <div class="mt-3">
            <label class="ft-label" for="password_confirmation">Confirm password <span class="text-red-500">*</span></label>
            <input id="password_confirmation" class="ft-input" type="password" name="password_confirmation" placeholder="*******" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button class="ft-button mt-6" type="submit">Create account</button>
    </form>
    <p class="ft-auth-footer">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
</x-guest-layout>
