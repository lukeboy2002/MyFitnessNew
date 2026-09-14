@props([
    'variant' => 'muscle',
    'icon' => null
])

@php
    $variants = [
        'muscle' => 'bg-muscle/30 border-muscle text-white',
        'musclegroup' => 'bg-musclegroup/30 border-musclegroup text-white',
        'exercise' => 'bg-exercise/30 border-exercise text-white',
        'bodypart' => 'bg-bodypart/30 border-bodypart text-white',
        'workout' => 'bg-workout/30 border-workout text-white',
        'cardio' => 'bg-blue-600/30 border-blue-600 text-white',
        'strength' => 'bg-orange-600/30 border-orange-600 text-white',
        'ghost' => 'border-transparent text-muted',
    ];

    $icons = [
        'muscle' => 'biceps-flexed',
        'exercise' => 'activity',
        'bodypart' => 'person-standing',
        'workout' => 'layers',
        'cardio' => 'heart',
        'strength' => 'dumbbell',
    ];

@endphp

<div
    {{ $attributes->merge([
        'class' => 'inline-flex items-center px-1.5 py-0.5 gap-1 rounded text-xs border transition duration-150 ease-in-out ' . ($variants[$variant] ?? $variants['muscle']),
    ]) }}
>
    @if($icon)
        <x-dynamic-component
            :component="'lucide-' . $icon"
            class="h-3 w-3"
        />
    @endif

    {{--    <x-icon name="{{ $icon }}" class="w-3 h-3"/>--}}

    {{ $slot }}
</div>
