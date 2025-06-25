@section('title', 'Login')

{!! NoCaptcha::renderJs() !!}
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="input-wrapper mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <i class="fa-solid fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="wrap-input100 validate-input mt-4">
            <div class="{{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                {!! NoCaptcha::display() !!}
            </div>
            @if ($errors->has('g-recaptcha-response'))
                <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
            @endif
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

    </form>

    <div class="flex items-center justify-end mt-4">
        <hr>
        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            href="{{ route('register') }}">
            {{ __("Don't have an account?") }}
        </a>
    </div>

    <div class="relative my-6 mt-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="bg-white px-2 text-gray-500">
                {{ __('Or') }}
            </span>
        </div>
    </div>

    <div class="flex items-center justify-center mt-4">
        <a href="{{ route('auth.google.redirect') }}"
            class="flex items-center justify-center w-full sm:w-auto border border-gray-300 rounded-md shadow-sm px-4 py-2 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition duration-150 ease-in-out">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo"
                class="w-5 h-5 mr-2">
            {{ __('Sign in with Google') }}
        </a>
    </div>
</x-guest-layout>
