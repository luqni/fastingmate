<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="d-block mt-1 w-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="d-block mt-1 w-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <div class="d-flex gap-4 mt-2">
                <label class="d-inline-d-flex align-items-center">
                    <input type="radio" name="gender" value="male" class="text-teal-600 focus:ring-teal-500 rounded-circle" {{ old('gender') === 'male' ? 'checked' : '' }} required>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Male (Laki-laki)</span>
                </label>
                <label class="d-inline-d-flex align-items-center">
                    <input type="radio" name="gender" value="female" class="text-teal-600 focus:ring-teal-500 rounded-circle" {{ old('gender') === 'female' ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Female (Perempuan)</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="d-block mt-1 w-100"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="d-block mt-1 w-100"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center justify-content-end mt-4">
            <a class="underline small text-gray-600 dark:text-muted hover:text-gray-900 dark:hover:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
