<x-guest-layout :pageTitle="__('Confirm Password')">
    <div class="mb-4 text-sm text-muted">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-form.label class="sr-only" for="password" :value="__('Password')"/>
            <x-form.input variant="auth"
                          type="password"
                          id="password"
                          name="password"
                          class="w-full"
                          required
                          autocomplete="current-password"/>
            <x-form.error :messages="$errors->get('password')"/>
        </div>

        <div class="flex justify-end mt-4">
            <x-button variant="primary">
                {{ __('Confirm') }}
            </x-button>
        </div>
    </form>
</x-guest-layout>
