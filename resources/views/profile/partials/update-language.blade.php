<x-card
    variant="outline"
    :title="__('Language')"
    :description="__('Update the language settings for your account.')"
>
    <fieldset aria-label="Choose a theme option">
        <div class="mt-6 grid grid-cols-3 gap-2">
            @foreach([
                ['value' => 'en', 'label' => 'English', 'icon' => 'lucide-flag'],
                ['value' => 'nl', 'label' => 'Dutch', 'icon' => 'lucide-flag'],
            ] as $option)
                <x-card-radio
                    wire:model.live="language"
                    name="language"
                    :value="$option['value']"
                    :active="(isset($language) ? $language : '') === $option['value']"
                    :icon="$option['icon']"
                >
                    {{ __($option['label']) }}
                </x-card-radio>
            @endforeach
        </div>
    </fieldset>
</x-card>
