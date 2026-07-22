<x-guest-layout :pageTitle="__('Forgot password')">

    <x-auth-session-status class="text-center" :status="session('status')"/>

    <div class="pt-4 mb-4 text-sm text-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
        @csrf

        <div>
            <x-form.label class="sr-only" for="login" :value="__('Email')"/>
            <x-form.input variant="auth"
                          type="email"
                          name="email"
                          id="email"
                          class="w-full"
                          :value="old('email')"
                          required
                          autofocus
                          placeholder="{{ __('Email') }}"/>
            <x-form.error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <div class="mt-8">
            <x-button variant="primary" class="w-full">
                {{ __('Email Password Reset Link') }}
            </x-button>
        </div>
    </form>

    <div class="flex justify-center items-center">
        <div class="text-sm text-primary">{{ __('Or, return to') }}</div>
        <x-link variant="ghost"
                href="{{ route('login') }}">
            {{ __('log in') }}
        </x-link>
    </div>

</x-guest-layout>
