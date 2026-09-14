@props([
    'title' => null,
    'description' => null,
    'variant' => 'outline',
])

@php
    $variants = [
        'outline' => 'p-4 border border-border bg-transparent',
        'ghost' => '',
        'primary' => 'p-4 border border-border bg-surface text-primary w-full',
        'secondary' => 'p-4 bg-surface text-primary',
        'success' => 'p-4 bg-success text-primary',
        'danger' => 'p-4 bg-error text-primary',
        'warning' => 'p-4 bg-warning text-primary',
        'info' => 'p-4 bg-info text-primary',
    ];
@endphp

<div {{ $attributes->merge([
    'class' => 'rounded-lg shadow-sm shadow-surface-secondary ' . ($variants[$variant] ?? $variants['outline'])
]) }}>
    <div class="flex items-center justify-between pb-1 mb-2">
        <div>
            <h2 class="text-lg font-semibold text-primary">{{ $title }}</h2>

            @if($description)
                <p class="text-xs italic text-muted">{{ $description }}</p>
            @endif
        </div>


    </div>
    @isset($actions)
        <div class="flex justify-end items-center gap-2">
            {{ $actions }}
        </div>
    @endisset

    {{ $slot }}
</div>
