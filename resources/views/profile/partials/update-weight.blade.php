<x-card
    variant="outline"
    :title="__('Weight')"
    :description="__('Update the weight settings for your account')"
>
    <fieldset aria-label="Choose a weight option">
        <div class="mt-6 grid grid-cols-3 gap-2">
            @foreach([
                ['value' => 'kg', 'label' => 'Kilogram', 'icon' => 'lucide-weight'],
                ['value' => 'lbs', 'label' => 'Ponds', 'icon' => 'lucide-weight'],
            ] as $option)
                <x-card-radio
                    wire:model.live="weight"
                    name="weight"
                    :value="$option['value']"
                    :active="(isset($weight) ? $weight : '') === $option['value']"
                    :icon="$option['icon']"
                >
                    {{ __($option['label']) }}
                </x-card-radio>
            @endforeach
        </div>
    </fieldset>
</x-card>
