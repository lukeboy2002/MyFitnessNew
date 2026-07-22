@props([
    'status',
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-success flex items-center gap-2']) }}>
        <x-lucide-circle-check class="h-4 w-4"/>{{ $status }}
    </div>
@endif
