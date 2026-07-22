@props([
    'value',
    'name',
    'active' => false,
    'icon' => null,
])

@php
    $inputAttributes = $attributes->only([
        'wire:model',
        'wire:model.live',
        'wire:click',
        'required',
        'disabled',
    ]);

    $labelAttributes = $attributes->except([
        'wire:model',
        'wire:model.live',
        'wire:click',
        'required',
        'disabled',
    ]);
@endphp

<label
    aria-label="{{ $value }}"
    {{ $labelAttributes->merge([
        'class' => 'relative flex items-center justify-center rounded-md border p-3 cursor-pointer transition-all duration-200'
            . ($active
                ? ' border-secondary'
                : ' border-border')
    ]) }}
>
    <input
        type="radio"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $inputAttributes }}
        class="sr-only"
    />

    {{--    @if($active)--}}
    {{--        <span class="absolute top-2 left-2 h-3 w-3 rounded-full bg-success"></span>--}}
    {{--    @endif--}}

    <span class="text-sm font-medium uppercase flex items-center gap-2">
        @if($icon)
            <x-dynamic-component
                :component="$icon"
                class="h-4 w-4 block md:hidden xl:block"
            />
        @endif

        {{ $slot->isEmpty() ? ucfirst($value) : $slot }}
    </span>
</label>
