<x-guest-layout :pageTitle="__('Reset password')">

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>

    <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
        @csrf
        <!-- Token -->
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <!-- Email Address -->
        <div class="mt-4">
            <x-form.label class="sr-only" for="email" :value="__('Email')"/>
            <x-form.input variant="auth"
                          type="email"
                          name="email"
                          id="email"
                          class="w-full"
                          value="{{ request('email') }}"
                          required
                          autocomplete="username"
                          placeholder="Email Address"/>
            <x-form.error :messages="$errors->get('email')"/>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-form.label class="sr-only" for="password" :value="__('Password')"/>
            <x-form.input variant="auth"
                          type="password"
                          name="password"
                          id="password"
                          class="w-full"
                          required
                          autocomplete="new-password"
                          passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                          placeholder="Password"
            />
            <x-form.error :messages="$errors->get('password')"/>
        </div>


        <!-- Confirm Password -->
        <div class="mt-4">
            <x-form.label class="sr-only" for="password_confirmation" :value="__('Confirm Password')"/>
            <x-form.input variant="auth"
                          type="password"
                          name="password_confirmation"
                          id="password_confirmation"
                          class="w-full"
                          required
                          autocomplete="new-password"
                          passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                          placeholder="Confirm Password"
            />
            <x-form.error :messages="$errors->get('password_confirmation')"/>
        </div>

        <div class="flex items-center justify-end">
            <x-button variant="primary" class="w-full" data-test="reset-password-button">
                {{ __('Reset password') }}
            </x-button>
        </div>
    </form>

</x-guest-layout>
