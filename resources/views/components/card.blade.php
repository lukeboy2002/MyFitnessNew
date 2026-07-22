@props([
    'title',
    'description' => null,
    'variant' => 'outline',
])

@php
    $variants = [
        'outline' => 'border border-border bg-transparent',
        'ghost' => '',
        'primary' => 'border border-border bg-surface text-primary w-full',
        'secondary' => 'bg-surface text-primary',
        'success' => 'bg-success text-primary',
        'danger' => 'bg-error text-primary',
        'warning' => 'bg-warning text-primary',
        'info' => 'bg-info text-primary',
    ];
@endphp

<div {{ $attributes->merge([
    'class' => 'rounded-lg p-4 shadow-sm shadow-surface-secondary ' . ($variants[$variant] ?? $variants['outline'])
]) }}>
    <div class="flex items-center justify-between border-b border-border pb-1 mb-2">
        <div>
            <h2 class="text-lg font-semibold text-primary">{{ $title }}</h2>

            @if($description)
                <p class="text-xs italic text-muted">{{ $description }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="flex gap-2">
                {{ $actions }}
            </div>
        @endisset
    </div>

    {{ $slot }}
</div>
