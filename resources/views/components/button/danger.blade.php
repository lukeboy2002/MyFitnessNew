@props([
    'icon' => null,
    'size' => 5,
    'variant' => 'outline',
])

@php
    $variants = [
    'outline' => 'border border-danger bg-transparent text-danger hover:text-primary rounded-lg',
    'ghost' => 'text-danger hover:text-primary',
    'fill' => 'border border-danger bg-danger text-white hover:bg-danger/90 rounded-lg',
];

@endphp

<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'flex items-center justify-center gap-2 px-2 py-2 transition duration-150 ease-in-out ' . ($variants[$variant] ?? $variants['outline']),
    ]) }}
>
    @if($icon)
        <x-dynamic-component
            :component="'lucide-' . $icon"
            class="h-{{ $size }} w-{{ $size }}"
        />
    @endif

    @if(trim($slot))
        <span class="text-sm">{{ $slot }}</span>
    @endif


</button>
