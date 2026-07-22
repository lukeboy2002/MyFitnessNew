<x-card
    variant="outline"
    :title="__('Avatar')"
    :description="__('Update your profile avatar.')"
>

    <div class="mt-6 flex items-center gap-6">
        <div class="relative">
            <img src="{{ $avatar ? $avatar->temporaryUrl() : $user->avatar_url }}"
                 alt="{{ $user->name }}"
                 class="w-24 h-24 rounded-full object-cover border-2 border-border">
            <div wire:loading wire:target="avatar"
                 class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full">
                <x-lucide-loader-2 class="w-6 h-6 animate-spin text-white"/>
            </div>
        </div>

        <form wire:submit="updateAvatar" class="space-y-4 flex-1">
            <div>
                <x-form.label for="avatar" :value="__('New Avatar Image')"/>
                <input type="file" wire:model="avatar" id="avatar" class="mt-1 px-4 block w-full text-sm text-muted
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-surface-hover file:text-primary
                        hover:file:bg-surface-alt">
                <x-form.error :messages="$errors->get('avatar')" class="mt-2"/>
            </div>

            <div class="flex items-center gap-4">
                <x-button variant="primary" type="submit" wire:loading.attr="disabled" wire:target="avatar"
                          class="w-full">
                    {{ __('Upload') }}
                </x-button>

                <p x-data="{ show: false }"
                   x-show="show"
                   x-on:avatar-updated.window="show = true; setTimeout(() => show = false, 2000)"
                   x-transition
                   style="display: none;"
                   class="text-sm text-success flex items-center gap-2"
                >
                    <x-lucide-check class="h-4 w-4"/>{{ __('Updated.') }}</p>
            </div>
        </form>
    </div>


</x-card>
