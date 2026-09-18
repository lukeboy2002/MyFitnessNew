@props([
    'title' => null,
    'description' => null,
    'exercise' => null,
    'icon' => null,
    'icon_size' => 3.5,
    'icon_color' => 'text-muted',
    'variant' => 'outline',
])

@php
    $variants = [
        'outline' => 'p-3 border border-border bg-surface-hover/50',
        'ghost' => '',
        'primary' => 'p-3 border border-border bg-surface text-primary w-full' ,
        'secondary' => 'p-3 bg-surface text-primary',
        'success' => 'p-3 bg-success text-primary',
        'danger' => 'p-3 bg-danger text-primary',
        'warning' => 'p-3 bg-warning/50 text-primary border border-warning',
        'info' => 'p-3 bg-info text-primary',
    ];
@endphp

<div {{ $attributes->merge([
    'class' => 'rounded-lg shadow-sm shadow-surface-secondary ' . ($variants[$variant] ?? $variants['outline'])
]) }}>
    <div class="flex flex-col">
        <div class="flex items-center gap-2 mb-1">
            @if($icon)
                <x-dynamic-component
                    :component="'lucide-' . $icon"
                    class="h-{{ $icon_size }} w-{{ $icon_size }} {{ $icon_color }}"
                />
            @endif
            @if($title)
                <div
                    class="text-nowrap bg-transparent text-muted text-xs">
                    {{ $title }}
                </div>

            @endif
        </div>
        @if($description)
            <p class="font-semibold text-primary">{{ $description }}</p>
        @endif
        @if($exercise)
            <p class="text-xs text-muted">{{ $exercise }}</p>
        @endif
    </div>
</div>
