@props(['href', 'icon'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex justify-between items-center border-b border-border px-4 py-4 last:border-0']) }}>
    <div class="flex items-center gap-2">
        <x-dynamic-component :component="'lucide-' . $icon" class="h-5 w-5"/>
        {{ $slot }}
    </div>
    <x-lucide-chevron-right class="h-4 w-4"/>
</a>
