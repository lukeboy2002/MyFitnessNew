@props([
    'default' => '',
])

<div
    x-data="{ tab: '{{ $default }}' }"
    {{ $attributes }}
>
    <div class="border-b border-zinc-200 dark:border-zinc-700">
        <nav class="-mb-px flex gap-6 overflow-x-auto">
            {{ $tabs }}
        </nav>
    </div>

    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
