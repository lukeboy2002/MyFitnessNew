@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-muted pb-1']) }}>
    {{ $value ?? $slot }}
</label>
