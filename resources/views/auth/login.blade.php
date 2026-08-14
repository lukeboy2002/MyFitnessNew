<x-guest-layout :pageTitle="__('Log in')">


    <x-card.default variant="primary">

        <x-auth-session-status class="text-center" :status="session('status')"/>

        <x-authenticate-passkey/>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6 pt-4">
            @csrf

            <!-- Email Address -->
            <div>
                <x-form.label class="sr-only" for="login" :value="__('Email or Username')"/>
                <x-form.input icon="user"
                              type="text"
                              name="email"
                              id="email"
                              :value="old('email')"
                              required
                              :placeholder="__('Email or Username')"
                />
                <x-form.error :messages="$errors->get('email')"/>
            </div>

            <!-- Password -->
            <div class="pt-2">
                <x-form.label class="sr-only" for="password" :value="__('Password')"/>
                <x-form.input icon="lock"
                              type="password"
                              name="password"
                              id="password"
                              class="w-full"
                              required
                              autocomplete="current-password"
                              :placeholder="__('Password')"/>
                <x-form.error :messages="$errors->get('password')"/>

            </div>
            <div class="flex justify-end -mt-6">
                @if (Route::has('password.request'))
                    <x-link.default variant="ghost" :href="route('password.request')">
                        {{ __('Forgot your password?') }}
                    </x-link.default>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2">
                <x-form.checkbox name="remember" id="remember_me"/>
                <x-form.label for="remember_me" class="text-sm text-muted" :value="__('Remember me')"/>
            </div>

            <div class="flex items-center justify-end">
                <x-button.default variant="primary" class="w-full">
                    {{ __('Log in') }}
                </x-button.default>
            </div>
        </form>

        <div class="flex justify-center items-center">
            <div class="text-sm text-primary">{{ __('New to MyFitness?') }}</div>
            <x-link.default variant="ghost"
                            href="{{ route('register') }}">
                {{ __('Sign up') }}
            </x-link.default>
        </div>
    </x-card.default>

</x-guest-layout>
