<x-guest-layout :pageTitle="__('Forgot password')">


    <x-card.default variant="primary" font_weight="black" text_size="text-2xl" text_color="text-secondary"
                    icon="lock-keyhole"
                    icon_size="6"
                    :title="__('Forgot Password')">
        <x-slot:description>
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </x-slot:description>

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <div>
                <x-form.label class="sr-only" for="login" :value="__('Email Address')"/>
                <x-form.input icon="mail"
                              type="email"
                              name="email"
                              id="email"
                              class="w-full"
                              :value="old('email')"
                              required
                              autofocus
                              :placeholder="__('Email Address')"/>
                <x-form.error :messages="$errors->get('email')" class="mt-2"/>
            </div>
            <x-auth-session-status class="text-center" :status="session('status')"/>

            <div class="mt-8">
                <x-button.default variant="primary" class="w-full">
                    {{ __('Email Password Reset Link') }}
                </x-button.default>
            </div>
        </form>

        <div class="flex justify-center items-center">
            <div class="text-sm text-primary">{{ __('Or, return to') }}</div>
            <x-link.default variant="ghost"
                            href="{{ route('login') }}">
                {{ __('log in') }}
            </x-link.default>
        </div>
    </x-card.default>
</x-guest-layout>
