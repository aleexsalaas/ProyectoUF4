<x-guest-layout>
    <!-- Barra superior con botones pegados a los bordes -->
    <div class="fixed top-0 left-0 w-full bg-white dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700 z-50 flex justify-between px-6 py-3">
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 font-semibold">
            ← {{ __('Go Back') }}
        </a>

        <x-responsive-nav-link :href="url('/properties')" :active="request()->is('properties*')">
                {{ __('Properties') }}
            </x-responsive-nav-link>
    </div>

    <!-- Espacio para que no quede tapado por la barra fija -->
    <div class="pt-16 max-w-md mx-auto px-4 sm:px-0">

        <!-- Mensaje de error general -->
        @if ($errors->has('login'))
            <div class="text-red-600 mb-4">
                {{ $errors->first('login') }}
            </div>
        @endif

        <!-- Formulario de login -->
        <form method="POST" action="{{ route('login') }}" class="bg-white dark:bg-gray-900 p-6 rounded shadow-md">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                @error('email')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                @error('password')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button>
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
