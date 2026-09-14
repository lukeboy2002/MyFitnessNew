@php
    use App\Enum\ExerciseType;

    $durationSeconds = $session->started_at && $session->completed_at
        ? $session->started_at->diffInSeconds(
            $session->completed_at
        )
        : 0;

    $durationHours = floor($durationSeconds / 3600);
    $durationMinutes = floor(
        ($durationSeconds % 3600) / 60
    );

    if ($durationHours > 0) {
        $formattedDuration =
            $durationHours . 'h ' .
            $durationMinutes . 'm';
    } else {
        $formattedDuration =
            $durationMinutes . ' min';
    }

    $completedSets = $session->workoutSets
        ->where('completed', true);

    $totalSets = $completedSets->count();

    $exerciseGroups = $completedSets
        ->filter(
            fn ($set) =>
                $set->workoutExerciseSet?->workoutExercise?->exercise
        )
        ->groupBy(
            fn ($set) =>
                $set
                    ->workoutExerciseSet
                    ->workoutExercise
                    ->exercise
                    ->id
        );
@endphp

<x-app-layout
    :title="__('Workout Summary')"
>
    <div class="max-w-5xl mx-auto">

        {{-- Back --}}
        <div class="mb-6">
            <x-link.default
                href="{{ route('dashboard') }}"
                variant="ghost"
                icon="arrow-left"
            >
                {{ __('Back to dashboard') }}
            </x-link.default>
        </div>


        {{-- Header --}}
        <div class="mb-8">

            <div class="flex flex-col gap-2">

                <div class="flex items-center gap-2">

                    <div
                        class="flex items-center justify-center
                        size-10 rounded-xl
                        bg-secondary/10 text-secondary"
                    >
                        <x-lucide-circle-check-big
                            class="size-6"
                        />
                    </div>

                    <div>

                        <p
                            class="text-sm text-muted"
                        >
                            {{ __('Workout completed') }}
                        </p>

                        <h1
                            class="text-2xl md:text-3xl
                            font-bold text-primary"
                        >
                            {{ $session->workout->name }}
                        </h1>

                    </div>

                </div>


                <p
                    class="text-sm text-muted"
                >
                    {{ $session->completed_at?->isoFormat(
                        'dddd D MMMM YYYY'
                    ) }}
                </p>

            </div>

        </div>


        {{-- Statistics --}}
        <div
            class="grid grid-cols-2
            lg:grid-cols-4 gap-4 mb-8"
        >

            {{-- Duration --}}
            <x-card.default
                variant="outline"
                class="p-4"
            >

                <div
                    class="flex items-center
                    justify-between mb-3"
                >

                    <span
                        class="text-xs uppercase
                        tracking-wider text-muted"
                    >
                        {{ __('Duration') }}
                    </span>

                    <x-lucide-clock
                        class="size-4 text-secondary"
                    />

                </div>

                <div
                    class="text-xl font-bold
                    text-primary"
                >
                    {{ $formattedDuration }}
                </div>

            </x-card.default>


            {{-- Exercises --}}
            <x-card.default
                variant="outline"
                class="p-4"
            >

                <div
                    class="flex items-center
                    justify-between mb-3"
                >

                    <span
                        class="text-xs uppercase
                        tracking-wider text-muted"
                    >
                        {{ __('Exercises') }}
                    </span>

                    <x-lucide-dumbbell
                        class="size-4 text-secondary"
                    />

                </div>

                <div
                    class="text-xl font-bold
                    text-primary"
                >
                    {{ $exerciseGroups->count() }}
                </div>

            </x-card.default>


            {{-- Sets --}}
            <x-card.default
                variant="outline"
                class="p-4"
            >

                <div
                    class="flex items-center
                    justify-between mb-3"
                >

                    <span
                        class="text-xs uppercase
                        tracking-wider text-muted"
                    >
                        {{ __('Completed sets') }}
                    </span>

                    <x-lucide-layers
                        class="size-4 text-secondary"
                    />

                </div>

                <div
                    class="text-xl font-bold
                    text-primary"
                >
                    {{ $totalSets }}
                </div>

            </x-card.default>


            {{-- Status --}}
            <x-card.default
                variant="outline"
                class="p-4"
            >

                <div
                    class="flex items-center
                    justify-between mb-3"
                >

                    <span
                        class="text-xs uppercase
                        tracking-wider text-muted"
                    >
                        {{ __('Status') }}
                    </span>

                    <x-lucide-circle-check
                        class="size-4 text-secondary"
                    />

                </div>

                <div
                    class="text-xl font-bold
                    text-secondary"
                >
                    {{ __('Completed') }}
                </div>

            </x-card.default>

        </div>


        {{-- Exercises --}}
        <div
            class="space-y-4"
        >

            <div
                class="flex items-center
                justify-between"
            >

                <h2
                    class="text-lg font-semibold
                    text-primary"
                >
                    {{ __('Exercises') }}
                </h2>

                <span
                    class="text-sm text-muted"
                >
                    {{ $exerciseGroups->count() }}
                    {{ __('exercises') }}
                </span>

            </div>


            @forelse (
                $exerciseGroups as $exerciseId => $sets
            )

                @php

                    $firstSet = $sets->first();

                    $exercise =
                        $firstSet
                            ->workoutExerciseSet
                            ->workoutExercise
                            ->exercise;

                @endphp


                <x-card.default
                    variant="outline"
                    class="overflow-hidden"
                >

                    {{-- Exercise Header --}}
                    <div
                        class="flex items-center
                        gap-4 p-4
                        border-b border-border"
                    >

                        {{-- Image --}}
                        @if ($exercise->image_path)

                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $exercise->image_path
                                ) }}"
                                alt="{{ $exercise->name }}"
                                class="
                                    size-14 rounded-xl
                                    object-cover
                                    border border-border
                                "
                            >

                        @else

                            <div
                                class="
                                    size-14 rounded-xl
                                    bg-surface-hover
                                    border border-border
                                    flex items-center
                                    justify-center
                                    text-muted
                                "
                            >

                                <x-lucide-dumbbell
                                    class="size-6"
                                />

                            </div>

                        @endif


                        {{-- Name --}}
                        <div
                            class="flex-1"
                        >

                            <div
                                class="flex items-center
                                gap-2"
                            >

                                <h3
                                    class="
                                        font-semibold
                                        text-primary
                                    "
                                >
                                    {{ $exercise->name }}
                                </h3>


                                <span
                                    class="
                                        px-2 py-0.5
                                        rounded
                                        text-[10px]
                                        font-medium
                                        bg-secondary/10
                                        text-secondary
                                    "
                                >
                                    {{ $exercise->type->value }}
                                </span>

                            </div>


                            <p
                                class="
                                    text-xs
                                    text-muted
                                    mt-1
                                "
                            >
                                {{ $sets->count() }}
                                {{ __('completed sets') }}
                            </p>

                        </div>

                    </div>


                    {{-- Strength --}}
                    @if (
                        $exercise->type
                        === ExerciseType::Strength
                    )

                        <div
                            class="overflow-x-auto"
                        >

                            <table
                                class="
                                    w-full
                                    text-sm
                                "
                            >

                                <thead
                                    class="
                                        text-xs
                                        text-muted
                                        bg-surface-hover/50
                                        border-b
                                        border-border
                                    "
                                >

                                <tr>

                                    <th
                                        class="
                                            text-left
                                            px-4 py-3
                                        "
                                    >
                                        {{ __('Set') }}
                                    </th>

                                    <th
                                        class="
                                            text-left
                                            px-4 py-3
                                        "
                                    >
                                        {{ __('Weight') }}
                                    </th>

                                    <th
                                        class="
                                            text-left
                                            px-4 py-3
                                        "
                                    >
                                        {{ __('Reps') }}
                                    </th>

                                </tr>

                                </thead>


                                <tbody
                                    class="
                                        divide-y
                                        divide-border/50
                                    "
                                >

                                @foreach (
                                    $sets as $index => $set
                                )

                                    <tr>

                                        <td
                                            class="
                                                px-4 py-3
                                                text-primary
                                                font-medium
                                            "
                                        >
                                            {{ $index + 1 }}
                                        </td>


                                        <td
                                            class="
                                                px-4 py-3
                                                text-primary
                                            "
                                        >

                                            {{ $set->weight
                                                ? number_format(
                                                    $set->weight,
                                                    1
                                                ) . ' kg'
                                                : '-'
                                            }}

                                        </td>


                                        <td
                                            class="
                                                px-4 py-3
                                                text-primary
                                            "
                                        >

                                            {{ $set->reps ?? '-' }}

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif


                    {{-- Cardio --}}
                    @if (
                        $exercise->type
                        === ExerciseType::Cardio
                    )

                        <div
                            class="
                                p-4
                                grid grid-cols-2
                                md:grid-cols-4
                                gap-4
                            "
                        >

                            {{-- Duration --}}
                            <div>

                                <p
                                    class="
                                        text-xs
                                        text-muted
                                        mb-1
                                    "
                                >
                                    {{ __('Duration') }}
                                </p>

                                <p
                                    class="
                                        font-semibold
                                        text-primary
                                    "
                                >

                                    @php

                                        $seconds =
                                            $sets->sum(
                                                'duration_seconds'
                                            );

                                        $minutes =
                                            floor(
                                                $seconds / 60
                                            );

                                        $remainingSeconds =
                                            $seconds % 60;

                                    @endphp


                                    @if ($minutes > 0)

                                        {{ $minutes }}m

                                        @if (
                                            $remainingSeconds > 0
                                        )

                                            {{ $remainingSeconds }}s

                                        @endif

                                    @else

                                        {{ $remainingSeconds }}s

                                    @endif

                                </p>

                            </div>


                            {{-- Distance --}}
                            <div>

                                <p
                                    class="
                                        text-xs
                                        text-muted
                                        mb-1
                                    "
                                >
                                    {{ __('Distance') }}
                                </p>

                                <p
                                    class="
                                        font-semibold
                                        text-primary
                                    "
                                >

                                    @php

                                        $distance =
                                            $sets->sum(
                                                fn ($set) =>
                                                    (float)
                                                    $set->distance_km
                                            );

                                    @endphp


                                    @if ($distance > 0)

                                        {{
                                            number_format(
                                                $distance,
                                                2
                                            )
                                        }}
                                        km

                                    @else

                                        -

                                    @endif

                                </p>

                            </div>


                            {{-- Average Speed --}}
                            <div>

                                <p
                                    class="
                                        text-xs
                                        text-muted
                                        mb-1
                                    "
                                >
                                    {{ __('Average speed') }}
                                </p>

                                <p
                                    class="
                                        font-semibold
                                        text-primary
                                    "
                                >

                                    @php

                                        $avgSpeed =
                                            $sets
                                                ->pluck(
                                                    'avg_speed'
                                                )
                                                ->filter()
                                                ->avg();

                                    @endphp


                                    @if ($avgSpeed)

                                        {{
                                            number_format(
                                                $avgSpeed,
                                                1
                                            )
                                        }}
                                        km/h

                                    @else

                                        -

                                    @endif

                                </p>

                            </div>


                            {{-- Calories --}}
                            <div>

                                <p
                                    class="
                                        text-xs
                                        text-muted
                                        mb-1
                                    "
                                >
                                    {{ __('Calories') }}
                                </p>

                                <p
                                    class="
                                        font-semibold
                                        text-primary
                                    "
                                >

                                    @php

                                        $calories =
                                            $sets->sum(
                                                'calories_total'
                                            );

                                    @endphp


                                    @if ($calories > 0)

                                        {{ $calories }}
                                        kcal

                                    @else

                                        -

                                    @endif

                                </p>

                            </div>

                        </div>

                    @endif


                </x-card.default>

            @empty

                <x-card.default
                    variant="outline"
                    class="
                        p-8
                        text-center
                    "
                >

                    <div
                        class="
                            flex flex-col
                            items-center gap-3
                        "
                    >

                        <div
                            class="
                                size-12
                                rounded-full
                                bg-surface-hover
                                flex items-center
                                justify-center
                                text-muted
                            "
                        >

                            <x-lucide-dumbbell
                                class="size-6"
                            />

                        </div>


                        <div>

                            <h3
                                class="
                                    font-medium
                                    text-primary
                                "
                            >
                                {{ __('No completed exercises') }}
                            </h3>


                            <p
                                class="
                                    text-sm
                                    text-muted
                                    mt-1
                                "
                            >
                                {{ __(
                                    'No completed sets were found for this workout.'
                                ) }}
                            </p>

                        </div>

                    </div>

                </x-card.default>

            @endforelse

        </div>


        {{-- Notes --}}
        @if ($session->notes)

            <x-card.default
                variant="outline"
                class="mt-6 p-5"
            >

                <div
                    class="
                        flex items-center
                        gap-2 mb-3
                    "
                >

                    <x-lucide-notebook-pen
                        class="
                            size-5
                            text-secondary
                        "
                    />

                    <h2
                        class="
                            font-semibold
                            text-primary
                        "
                    >
                        {{ __('Notes') }}
                    </h2>

                </div>


                <p
                    class="
                        text-sm
                        text-muted
                        whitespace-pre-line
                    "
                >
                    {{ $session->notes }}
                </p>

            </x-card.default>

        @endif


        {{-- Bottom actions --}}
        <div
            class="
                flex justify-between
                items-center
                gap-4
                mt-8 mb-8
            "
        >

            <x-link.default
                href="{{ route('dashboard') }}"
                variant="outline"
                icon="house"
            >
                {{ __('Dashboard') }}
            </x-link.default>
            
        </div>

    </div>
</x-app-layout>
