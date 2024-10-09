<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Google Login Button -->
    <div class="mb-4">
        <a href="{{ route('google.login') }}" class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="mr-2" viewBox="0 0 20 20" fill="none">
                <path fill="#4285F4" d="M10 0C4.477 0 0 4.477 0 10c0 5.523 4.477 10 10 10 5.523 0 10-4.477 10-10S15.523 0 10 0zm3.025 10.276c.169.796.083 1.651-.24 2.388-.458.211-.99.323-1.563.323-1.292 0-2.53-.553-3.408-1.637-.41-.49-.733-1.052-.95-1.67l1.293-.042c.152.522.406 1.014.764 1.428.485.561 1.14.85 1.836.85.351 0 .694-.073 1.014-.205.159-.143.298-.305.42-.489.156-.237.239-.516.239-.82 0-.369-.128-.709-.352-.985-.233-.283-.563-.46-.93-.46-.407 0-.772.213-.947.549l-1.179.228c.09-.357.31-.666.638-.849.237-.129.522-.202.815-.202.5 0 .979.192 1.327.541.388.393.554.876.554 1.396 0 .279-.063.563-.179.809-.111.225-.293.44-.521.632-.418.388-.958.59-1.503.59-.836 0-1.617-.331-2.198-.93-.575-.577-.895-1.394-.895-2.236 0-.187.015-.377.043-.563l1.243-.024c.018.124.027.245.027.368 0 .891.218 1.776.628 2.494.133.242.318.451.539.644.48.438 1.117.674 1.761.674.638 0 1.251-.249 1.698-.678.469-.438.736-1.022.736-1.623 0-.391-.117-.766-.34-1.085-.232-.329-.563-.573-.94-.73z"/>
            </svg>
            <span class="ml-2">Log in with Google</span>
        </a>
    </div>
    
    <!-- Separation Below the Button -->
    <div class="border-b border-gray-300 mb-4"></div>
    
    

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
