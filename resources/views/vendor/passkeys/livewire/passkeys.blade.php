<x-card
    variant="outline"
    :title="__('Passkey')"
    :description="__('Ensure your account is using a long, random password to stay secure.')"
>

    <div class="mt-2">
        <form id="passkeyForm" wire:submit="validatePasskeyProperties" class="space-y-6">
            <div>
                <x-form.label for="name">{{ __('passkeys::passkeys.name') }}</x-form.label>
                <x-form.input autocomplete="off" type="text" wire:model="name"/>
                <x-form.error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            <x-button variant="primary" type="submit" class="w-full">
                {{ __('passkeys::passkeys.create') }}
            </x-button>
        </form>
    </div>

    <div class="mt-6">
        <ul class="space-y-4">
            @foreach($passkeys as $passkey)
                <li class="flex justify-between items-center p-4 bg-surface-hover border border-border rounded-lg shadow-sm">
                    <div class="text-primary">
                        {{ $passkey->name }}
                    </div>
                    <div class="text-xs">
                        {{ __('passkeys::passkeys.last_used') }}
                        : {{ $passkey->last_used_at?->diffForHumans() ?? __('passkeys::passkeys.not_used_yet') }}
                    </div>

                    <div>
                        <x-button variant="danger"
                                  icon="trash"
                                  size="4"
                                  wire:click="deletePasskey({{ $passkey->id }})">
                        </x-button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-card>

@include('passkeys::livewire.partials.createScript')
