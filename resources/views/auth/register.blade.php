<x-guest-layout :pageTitle="__('Register')">

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
        @csrf
        <div>
            <x-form.label class="sr-only" for="name" :value="__('Name')"/>
            <x-form.input variant="auth"
                          type="text"
                          name="name"
                          id="name"
                          class="w-full"
                          :value="old('name')"
                          required
                          autofocus
                          autocomplete="name"
                          placeholder="Full Name"/>
            <x-form.error :messages="$errors->get('name')"/>
        </div>

        <div>
            <x-form.label class="sr-only" for="username" :value="__('Username')"/>
            <x-form.input variant="auth"
                          type="text"
                          name="username"
                          id="username"
                          class="w-full"
                          :value="old('username')"
                          required
                          autofocus
                          autocomplete="username"
                          placeholder="Username"/>
            <x-form.error :messages="$errors->get('username')"/>
        </div>

        <div class="mt-4">
            <x-form.label class="sr-only" for="email" :value="__('Email')"/>
            <x-form.input variant="auth"
                          type="email"
                          name="email"
                          id="email"
                          class="w-full"
                          :value="old('email')"
                          required
                          autocomplete="username"
                          placeholder="Email Address"/>
            <x-form.error :messages="$errors->get('email')"/>
        </div>

        <div class="mt-4">
            <x-form.label class="sr-only" for="password" :value="__('Password')"/>
            <x-form.input variant="auth"
                          type="password"
                          name="password"
                          id="password"
                          class="w-full"
                          required
                          autocomplete="new-password"
                          placeholder="Password"/>
            <x-form.error :messages="$errors->get('password')"/>
        </div>
        <div class="mt-4">
            <x-form.label class="sr-only" for="password_confirmation" :value="__('Confirm Password')"/>
            <x-form.input variant="auth"
                          type="password"
                          name="password_confirmation"
                          id="password_confirmation"
                          class="w-full"
                          required
                          autocomplete="new-password"
                          placeholder="Confirm Password"
            />
            <x-form.error :messages="$errors->get('password_confirmation')"/>
        </div>

        <x-button variant="primary" class="w-full">
            {{ __('Register') }}
        </x-button>
    </form>

    <div class="flex justify-center items-center">
        <div class="text-sm text-primary">{{ __('Already have an account?') }}</div>
        <x-link variant="ghost"
                href="{{ route('login') }}">
            {{ __('Log in') }}
        </x-link>
    </div>
</x-guest-layout>
