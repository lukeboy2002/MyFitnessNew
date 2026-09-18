@props([
    'title' => null,
    'description' => null,
    'text_size' => 'text-sm',
    'text_color' => 'text-muted',
    'font_weight' => 'medium',
    'icon' => null,
    'icon_size' => 5,
    'variant' => 'outline',
])

@php
    $variants = [
        'outline' => 'p-4 border border-border bg-transparent',
        'ghost' => '',
        'primary' => 'p-4 border border-border bg-surface text-primary w-full' ,
        'secondary' => 'p-4 bg-surface text-primary',
        'success' => 'p-4 bg-success text-primary',
        'danger' => 'p-4 bg-danger text-primary',
        'warning' => 'p-4 bg-warning/50 text-primary border border-warning',
        'info' => 'p-4 bg-info text-primary',
    ];
@endphp

<div {{ $attributes->merge([
    'class' => 'rounded-lg shadow-sm shadow-surface-secondary ' . ($variants[$variant] ?? $variants['outline'])
]) }}>
    <div class="flex flex-col">
        <div class="flex items-center gap-1">
            @if($icon)
                <x-dynamic-component
                    :component="'lucide-' . $icon"
                    class="h-{{ $icon_size }} w-{{ $icon_size }} {{ $text_color }}"
                />
            @endif

            @if($title)
                <div
                    class="{{ $text_size }} {{ $text_color }} {{ $font_weight }} text-nowrap bg-transparent">
                    {{ $title }}
                </div>

            @endif
        </div>

        @if($description)
            <p class="pt-4 text-xs italic text-muted">{{ $description }}</p>
        @endif

    </div>

    {{ $slot }}

    @isset($actions)
        <div class="flex gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
