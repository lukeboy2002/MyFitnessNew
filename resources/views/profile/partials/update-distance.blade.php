<x-card
    variant="outline"
    :title="__('Distance')"
    :description="__('Update the distance settings for your account.')"
>
    <fieldset aria-label="Choose a distance option">
        <div class="mt-6 grid grid-cols-3 gap-2">
            @foreach([
                ['value' => 'km', 'label' => 'Kilometer', 'icon' => 'lucide-ruler-dimension-line'],
                ['value' => 'mi', 'label' => 'Miles', 'icon' => 'lucide-ruler-dimension-line'],
            ] as $option)
                <x-card-radio
                    wire:model.live="distance"
                    name="distance"
                    :value="$option['value']"
                    :active="$distance === $option['value']"
                    :icon="$option['icon']"
                >
                    {{ __($option['label']) }}
                </x-card-radio>
            @endforeach
        </div>
    </fieldset>


    {{--    <fieldset aria-label="Choose a distance option">--}}
    {{--        <div class="mt-6 grid grid-cols-2 gap-3">--}}
    {{--            <x-card-radio wire:model.live="distance" name="distance" value="km"--}}
    {{--                          icon="lucide-ruler-dimension-line">--}}
    {{--                {{ __('Kilometers') }}--}}
    {{--            </x-card-radio>--}}

    {{--            <x-card-radio wire:model.live="distance" name="distance" value="mi"--}}
    {{--                          icon="lucide-ruler-dimension-line">--}}
    {{--                {{ __('Miles') }}--}}
    {{--            </x-card-radio>--}}
    {{--        </div>--}}

    {{--    </fieldset>--}}
</x-card>
