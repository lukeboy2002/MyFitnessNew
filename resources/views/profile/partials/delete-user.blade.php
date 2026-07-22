<div>
    <x-card variant="outline"
            title="{{ __('Delete Account') }}">
        <p class="mt-4 text-sm text-primary">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

        <x-button variant="danger"
                  class="w-full mt-6"
                  x-data=""
                  x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Delete Account') }}
        </x-button>
    </x-card>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">
            <h2 class="text-lg font-medium text-primary-600">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-muted">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-form.label for="password" value="{{ __('Password') }}" class="sr-only"/>

                <x-form.input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />
                <x-form.error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <div class="mt-6 flex justify-end">
                <x-button variant="outline" class="w-full" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-button>

                <x-button variant="danger" class="w-full ms-3">
                    {{ __('Delete Account') }}
                </x-button>
            </div>
        </form>
    </x-modal>
</div>
