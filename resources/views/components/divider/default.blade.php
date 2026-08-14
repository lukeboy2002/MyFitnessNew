@props([
    'title' => null,
    'size' => 'text-sm',
    'color' => 'text-muted',
    'weight' => 'medium'
])

<div class="flex items-center pt-4 sm:pt-6">
    <div aria-hidden="true" class="w-full border-t border-border"></div>
    <div class="relative flex justify-center items-center">
        <span
            class="px-2 {{ $size }} {{ $color }} {{ $weight }} text-nowrap bg-transparent">{{ $title }}</span>
    </div>
    <div aria-hidden="true" class="w-full border-t border-border"></div>
</div>

