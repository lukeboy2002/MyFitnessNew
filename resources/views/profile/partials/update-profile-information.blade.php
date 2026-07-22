<x-card
    variant="outline"
    :title="__('Profile Information')"
    :description="__('Update your profile information and email address.')"
>
    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-form.label for="username" :value="__('Username')"/>
            <x-form.input name="username" autocomplete="off" type="text" class="w-full" wire:model="username" required
                          autofocus
                          autocomplete="username"/>
            <x-form.error class="mt-2" :messages="$errors->get('username')"/>
        </div>

        <div>
            <x-form.label for="name" :value="__('Name')"/>
            <x-form.input name="name" autocomplete="off" type="text" class="w-full" wire:model="name" required autofocus
                          autocomplete="name"/>
            <x-form.error class="mt-2" :messages="$errors->get('name')"/>
        </div>

        <div>
            <x-form.label for="email" :value="__('Email')"/>
            <x-form.input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required
                          autocomplete="username" :value="$email"/>
            <x-form.error class="mt-2" :messages="$errors->get('email')"/>

            @if (auth()->user() instanceof MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-muted">
                        {{ __('Your email address is unverified.') }}

                        <button type="button" wire:click="sendVerification"
                                class="underline text-sm text-primary hover:text-secondary rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-button variant="primary" class="w-full">{{ __('Save') }}</x-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success flex items-center"
                >
                    <x-lucide-check-circle class="h-4 w-4 mr-1"/>{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</x-card>
