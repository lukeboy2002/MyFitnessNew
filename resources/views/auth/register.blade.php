<x-guest-layout :pageTitle="__('Register')">

    <!-- Session Status -->
    <x-auth-session-status class="text-center pb-1" :status="session('status')"/>

    <x-card.default variant="primary" font_weight="black" text_size="text-2xl" text_color="text-secondary">

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
            @csrf
            <div class="mt-4">
                <x-form.label class="sr-only" for="name" :value="__('Full Name')"/>
                <x-form.input icon="user"
                              type="text"
                              name="name"
                              id="name"
                              class="w-full"
                              :value="old('name')"
                              required
                              autofocus
                              autocomplete="name"
                              :placeholder="__('Full Name')"/>
                <x-form.error :messages="$errors->get('name')"/>
            </div>

            <div class="mt-4">
                <x-form.label class="sr-only" for="username" :value="__('Username')"/>
                <x-form.input icon="circle-user-round"
                              type="text"
                              name="username"
                              id="username"
                              class="w-full"
                              :value="old('username')"
                              required
                              autofocus
                              autocomplete="username"
                              :placeholder="__('Username')"/>
                <x-form.error :messages="$errors->get('username')"/>
            </div>

            <div class="mt-4">
                <x-form.label class="sr-only" for="email" :value="__('Email Address')"/>
                <x-form.input icon="mail"
                              type="email"
                              name="email"
                              id="email"
                              class="w-full"
                              :value="old('email')"
                              required
                              autocomplete="username"
                              :placeholder="__('Email Address')"/>
                <x-form.error :messages="$errors->get('email')"/>
            </div>

            <div class="mt-4">
                <x-form.label class="sr-only" for="password" :value="__('Password')"/>
                <x-form.input icon="lock"
                              type="password"
                              name="password"
                              id="password"
                              class="w-full"
                              required
                              autocomplete="new-password"
                              :placeholder="__('Password')"/>
                <x-form.error :messages="$errors->get('password')"/>
            </div>
            <div class="mt-4">
                <x-form.label class="sr-only" for="password_confirmation" :value="__('Confirm Password')"/>
                <x-form.input icon="lock-keyhole"
                              type="password"
                              name="password_confirmation"
                              id="password_confirmation"
                              class="w-full"
                              required
                              autocomplete="new-password"
                              :placeholder="__('Confirm Password')"
                />
                <x-form.error :messages="$errors->get('password_confirmation')"/>
            </div>

            <x-button.default variant="primary" class="w-full">
                {{ __('Register') }}
            </x-button.default>
        </form>

        <div class="flex justify-center items-center">
            <div class="text-sm text-primary">{{ __('Already have an account?') }}</div>
            <x-link.default variant="ghost"
                            href="{{ route('login') }}">
                {{ __('Log in') }}
            </x-link.default>
        </div>
    </x-card.default>
</x-guest-layout>
