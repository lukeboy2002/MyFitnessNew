<x-card
    variant="outline"
    :title="__('Appearance')"
    :description="__('Update the appearance settings for your account.')"
>

    <fieldset aria-label="Choose a theme option">
        <div class="mt-6 grid grid-cols-3 gap-2">
            @foreach([
                ['value' => 'light', 'label' => 'Light', 'icon' => 'lucide-sun'],
                ['value' => 'dark', 'label' => 'Dark', 'icon' => 'lucide-moon'],
                ['value' => 'system', 'label' => 'System', 'icon' => 'lucide-computer'],
            ] as $option)
                <x-card-radio
                    wire:model.live="theme"
                    name="appearance"
                    :value="$option['value']"
                    :active="(isset($theme) ? $theme : '') === $option['value']"
                    :icon="$option['icon']"
                >
                    {{ __($option['label']) }}
                </x-card-radio>
            @endforeach
        </div>
    </fieldset>
</x-card>
