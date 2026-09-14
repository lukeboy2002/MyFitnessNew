@props([
    'id',                 // unieke id basis, bv. "bold"
    'sr' => '',           // screenreader label
    'title' => '',        // tooltip text
    'class' => '',        // extra button classes
    'icon' => null,       // lucide icon-naam, bv. "bold"
    'iconClass' => 'w-5 h-5', // classes voor het icon
])

@php
    $btnId = $id ? "toggle{$id}Button" : 'btn-' . \Illuminate\Support\Str::uuid();
    $tooltipId = $id ? "tooltip{$id}" : 'tooltip-' . \Illuminate\Support\Str::uuid();
@endphp

<button
    id="{{ $btnId }}"
    type="button"
    data-tooltip-target="{{ $tooltipId }}"
    {{ $attributes->merge([
        'class' => "p-1.5 text-primary-muted rounded-sm cursor-pointer hover:text-primary hover:bg-surface-hover {$class}"
    ]) }}
>
    @if($icon)
        <x-dynamic-component :component="'lucide-' . $icon" :class="$iconClass"/>
    @else
        {{ $slot }}
    @endif

    @if($sr)
        <span class="sr-only">{{ $sr }}</span>
    @endif
</button>

<div
    id="{{ $tooltipId }}"
    role="tooltip"
    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-primary transition-opacity duration-300 bg-surface rounded-lg shadow-xs opacity-0 tooltip"
>
    {{ $title }}
    <div class="tooltip-arrow" data-popper-arrow></div>
</div>
