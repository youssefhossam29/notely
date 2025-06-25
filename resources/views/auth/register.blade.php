@section('title', 'Register')

{!! NoCaptcha::renderJs() !!}
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="input-wrapper mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <i class="fa-solid fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
        </div>

        <!-- Confirm Password -->
        <div class="input-wrapper mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>


        <div class="wrap-input100 validate-input mt-4">
            <div class="{{$errors->has('g-recaptcha-response')? 'has-error' : ''}}">
                        {!! NoCaptcha::display() !!}
            </div>
            @if ($errors->has('g-recaptcha-response'))
                <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
            @endif
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

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
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo" class="w-5 h-5 mr-2">
            {{ __("Sign in with Google") }}
        </a>
    </div>

</x-guest-layout>
