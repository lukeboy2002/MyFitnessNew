@props([
    'type' => 'text',
    'variant' => 'default', // default | auth
])

@php
    $base = 'border border-border text-primary text-sm block w-full px-3 py-2.5 placeholder:text-muted focus:outline-none';
    $default = 'bg-surface rounded-md focus:border-secondary focus:ring-0 shadow-xs';
    // Auth variant: slightly different radius, background, and focus ring style
    $auth = 'rounded-xl focus:ring-2 focus:ring-secondary/50';

    $classes = trim($base.' '.($variant === 'auth' ? $auth : $default));
@endphp

@if($variant === 'auth')
    <div class="relative mt-2">
        <input type="{{ $type }}" {{ $attributes->merge([
               'class' => 'peer block border-0 w-full bg-transparent px-3 py-1.5 text-sm text-muted placeholder:text-muted focus:outline-none focus:ring-0' ]) }}/>
        <div aria-hidden="true"
             class="absolute inset-x-0 bottom-0 border-t border-muted peer-focus:border-t-2 peer-focus:border-secondary"></div>
    </div>
@else
    <input type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}/>
@endif


