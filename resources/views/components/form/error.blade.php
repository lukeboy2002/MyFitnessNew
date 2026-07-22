@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-2 mb-4 font-medium text-xs text-danger flex gap-2 items-center']) }}>
        @foreach ($messages as $message)
            <li class="flex gap-2 items-center">
                <x-lucide-triangle-alert class="h-4 w-4"/>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
