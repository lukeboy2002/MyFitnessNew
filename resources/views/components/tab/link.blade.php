@props([
    'name',
])

<button
    type="button"
    @click="tab = '{{ $name }}'"
    class="border-b-2 px-1 py-4 text-sm font-medium transition-colors"
    :class="tab === '{{ $name }}'
        ? 'border-secondary text-secondary'
        : 'border-transparent text-muted hover:text-secondary hover:secondary'"
>
    {{ $slot }}
</button>
