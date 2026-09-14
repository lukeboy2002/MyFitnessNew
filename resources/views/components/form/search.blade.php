@props([
    'icon' => null,
    'prefixText' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
])

@php
    $hasPrefix = filled($icon) || filled($prefixText);
@endphp

<div class="flex shadow-xs rounded-md group">
    @if ($hasPrefix)
        <span
            class="inline-flex items-center px-3 text-sm text-muted bg-transparent border border-border border-e-0 rounded-s-md group-focus-within:border-secondary">
            @if (filled($icon))
                <x-dynamic-component :component="'lucide-' . $icon"
                                     class="w-4 h-4 text-muted group-focus-within:text-secondary"/>
            @else
                {{ $prefixText }}
            @endif
        </span>
    @endif

    <input
        type="{{ $type }}"
        @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes->merge([
            'class' => 'block w-full px-3 py-2 bg-transparent border border-border text-heading text-sm '
                . ($hasPrefix ? 'rounded-e-md' : 'rounded-md')
                . ' focus:border-secondary focus:ring-0 placeholder:text-muted',
        ]) }}
    >
</div>
