@props([
    'icon' => null,
    'size' => 5,
    'variant' => 'outline',
])

@php
    $variants = [
    'outline' => 'border border-border bg-transparent text-primary hover:text-secondary',
    'ghost' => 'text-primary hover:text-secondary',
    'primary' => 'bg-secondary text-white hover:bg-secondary/90',
    'success' => 'border border-border bg-transparent text-succes hover:text-secondary',
    'danger' => 'border border-danger bg-danger text-white hover:bg-danger/90',
    'warning' => 'border border-border bg-transparent text-warning hover:text-secondary',
    'info' => 'border border-border bg-transparent text-info hover:text-secondary',
];

@endphp

<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'flex items-center justify-center gap-2 px-2 py-2 rounded-lg transition duration-150 ease-in-out ' . ($variants[$variant] ?? $variants['outline']),
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
