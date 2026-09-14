<x-app-layout pageTitle="Dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary flex items-center gap-1.5">
            Hoi, {{ auth()->user()->username }}!
            <span>
            <x-lucide-hand class="w-8 h-8 text-secondary"/>
                </span>
        </h1>
        <p class="text-muted text-sm mt-1">
            {{ now()->isoFormat('dddd D MMMM') }}
        </p>

        <div class="flex flex-col gap-6 w-full mt-6">
            <div class="flex flex-col md:flex-row justify-between gap-6 w-full">

                {{--                LAST WORKOUT--}}
                <x-card.default
                    variant="outline"
                    class="w-full"
                >

                    {{-- Header --}}
                    <div class="flex items-center gap-2 mb-5">

                        <div class="p-2 rounded-lg bg-secondary/10 text-secondary">
                            <x-lucide-dumbbell class="w-5 h-5"/>
                        </div>

                        <div>
                            <h2 class="font-semibold text-primary">
                                {{ __('Last workout') }}
                            </h2>

                            <p class="text-xs text-muted">
                                {{ __('Your latest completed workout') }}
                            </p>
                        </div>

                    </div>


                    @if ($lastWorkout)

                        {{-- Workout name --}}
                        <div class="mb-5">

                            <h3 class="text-lg font-semibold text-primary">
                                {{ $lastWorkout->workout->name }}
                            </h3>

                            <p class="text-xs text-muted mt-1">
                                {{ $lastWorkout->completed_at->diffForHumans() }}
                            </p>

                        </div>


                        {{-- Statistics --}}
                        <div class="grid grid-cols-3 gap-3 mb-5">

                            {{-- Duration --}}
                            <div
                                class="bg-surface-hover/50 rounded-xl p-3"
                            >

                                <div class="flex items-center gap-1 text-muted mb-1">

                                    <x-lucide-clock class="w-3.5 h-3.5"/>

                                    <span class="text-xs">
                        {{ __('Duration') }}
                    </span>

                                </div>

                                <p class="font-semibold text-primary">
                                    {{ $lastWorkout->formatted_duration }}
                                </p>

                            </div>


                            {{-- Exercises --}}
                            <div
                                class="bg-surface-hover/50 rounded-xl p-3"
                            >

                                <div class="flex items-center gap-1 text-muted mb-1">

                                    <x-lucide-dumbbell
                                        class="w-3.5 h-3.5"
                                    />

                                    <span class="text-xs">
                        {{ __('Exercises') }}
                    </span>

                                </div>

                                <p class="font-semibold text-primary">

                                    {{ $lastWorkout
                                        ->workout
                                        ->workoutExercises
                                        ->count() }}

                                </p>

                            </div>


                            {{-- Completed Sets --}}
                            <div
                                class="bg-surface-hover/50 rounded-xl p-3"
                            >

                                <div class="flex items-center gap-1 text-muted mb-1">

                                    <x-lucide-check
                                        class="w-3.5 h-3.5"
                                    />

                                    <span class="text-xs">
                        {{ __('Sets') }}
                    </span>

                                </div>

                                <p class="font-semibold text-primary">

                                    {{ $lastWorkout
                                        ->workoutSets
                                        ->where('completed', true)
                                        ->count() }}

                                </p>

                            </div>

                        </div>


                        {{-- View Summary --}}
                        <x-link.default
                            href="{{ route('sessions.summary', $lastWorkout) }}"
                            variant="outline"
                            class="w-full"
                        >
            <span class="flex items-center justify-center gap-2">

                {{ __('View summary') }}

                <x-lucide-arrow-right
                    class="w-4 h-4"
                />

            </span>
                        </x-link.default>

                    @else

                        {{-- Empty state --}}
                        <div class="py-8 text-center">

                            <div
                                class="w-12 h-12 mx-auto mb-3 rounded-xl bg-surface-hover flex items-center justify-center"
                            >
                                <x-lucide-dumbbell
                                    class="w-6 h-6 text-muted"
                                />
                            </div>

                            <p class="text-sm font-medium text-primary">
                                {{ __('No workouts yet') }}
                            </p>

                            <p class="text-xs text-muted mt-1">
                                {{ __('Complete your first workout to see it here.') }}
                            </p>

                        </div>

                    @endif

                </x-card.default>

                {{--                THIS WEEK--}}
                <x-card.default
                    variant="outline"
                    class="w-full"
                >

                    {{-- Header --}}
                    <div class="flex items-center gap-2 mb-5">

                        <div class="p-2 rounded-lg bg-secondary/10 text-secondary">

                            <x-lucide-calendar-days
                                class="w-5 h-5"
                            />

                        </div>

                        <div>

                            <h2 class="font-semibold text-primary">
                                {{ __('This week') }}
                            </h2>

                            <p class="text-xs text-muted">
                                {{ now()->startOfWeek()->format('d M') }}
                                -
                                {{ now()->endOfWeek()->format('d M') }}
                            </p>

                        </div>

                    </div>


                    {{-- Statistics --}}
                    <div class="grid grid-cols-3 gap-3">

                        {{-- Workouts --}}
                        <div
                            class="bg-surface-hover/50 rounded-xl p-4"
                        >

                            <div class="flex items-center gap-1 text-muted mb-2">

                                <x-lucide-dumbbell
                                    class="w-3.5 h-3.5"
                                />

                                <span class="text-xs">
                    {{ __('Workouts') }}
                </span>

                            </div>

                            <p class="text-2xl font-bold text-primary">
                                {{ $workoutsThisWeek }}
                            </p>

                        </div>


                        {{-- Sets --}}
                        <div
                            class="bg-surface-hover/50 rounded-xl p-4"
                        >

                            <div class="flex items-center gap-1 text-muted mb-2">

                                <x-lucide-list-checks
                                    class="w-3.5 h-3.5"
                                />

                                <span class="text-xs">
                    {{ __('Sets') }}
                </span>

                            </div>

                            <p class="text-2xl font-bold text-primary">
                                {{ $totalSetsThisWeek }}
                            </p>

                        </div>


                        {{-- Training Time --}}
                        <div
                            class="bg-surface-hover/50 rounded-xl p-4"
                        >

                            <div class="flex items-center gap-1 text-muted mb-2">

                                <x-lucide-clock
                                    class="w-3.5 h-3.5"
                                />

                                <span class="text-xs">
                    {{ __('Time') }}
                </span>

                            </div>


                            <p class="text-2xl font-bold text-primary">

                                {{ $totalTrainingTimeThisWeek }}


                            </p>

                        </div>

                    </div>

                </x-card.default>
            </div>

            {{--           PERSONAL RECORDS--}}
            <x-card.default variant="outline" class="w-full">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-primary">
                            {{ __('Personal Records') }}
                        </h2>

                        <p class="text-sm text-muted mt-1">
                            {{ __('Your best performances') }}
                        </p>

                        <a href="{{ route('personal-records.index') }}">View all</a>
                    </div>

                    <x-lucide-trophy
                        class="w-6 h-6 text-secondary"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                    {{-- Highest Weight --}}
                    <div class="bg-surface rounded-xl border border-border p-4">

                        <div class="flex items-center gap-2 mb-3">
                            <x-lucide-dumbbell
                                class="w-5 h-5 text-secondary"
                            />

                            <span class="text-sm text-muted">
                    {{ __('Highest Weight') }}
                </span>
                        </div>

                        @if ($highestWeight)

                            <div class="text-2xl font-bold text-primary">
                                {{ number_format($highestWeight->weight, 1) }}
                                <span class="text-sm font-normal text-muted">
                        kg
                    </span>
                            </div>

                            <p class="text-sm text-primary mt-2 truncate">
                                {{ $highestWeight
                                    ?->workoutExerciseSet
                                    ?->workoutExercise
                                    ?->exercise
                                    ?->name }}
                            </p>

                        @else

                            <p class="text-sm text-muted">
                                {{ __('No record yet') }}
                            </p>

                        @endif

                    </div>


                    {{-- Most Reps --}}
                    <div class="bg-surface rounded-xl border border-border p-4">

                        <div class="flex items-center gap-2 mb-3">
                            <x-lucide-repeat
                                class="w-5 h-5 text-secondary"
                            />

                            <span class="text-sm text-muted">
                    {{ __('Most Reps') }}
                </span>
                        </div>

                        @if ($mostReps)

                            <div class="text-2xl font-bold text-primary">
                                {{ $mostReps->reps }}
                                <span class="text-sm font-normal text-muted">
                        reps
                    </span>
                            </div>

                            <p class="text-sm text-primary mt-2 truncate">
                                {{ $mostReps
                                    ?->workoutExerciseSet
                                    ?->workoutExercise
                                    ?->exercise
                                    ?->name }}
                            </p>

                        @else

                            <p class="text-sm text-muted">
                                {{ __('No record yet') }}
                            </p>

                        @endif

                    </div>


                    {{-- Longest Duration --}}
                    <div class="bg-surface rounded-xl border border-border p-4">

                        <div class="flex items-center gap-2 mb-3">
                            <x-lucide-clock
                                class="w-5 h-5 text-secondary"
                            />

                            <span class="text-sm text-muted">
                    {{ __('Longest Duration') }}
                </span>
                        </div>

                        @if ($longestDuration)

                            @php
                                $duration = $longestDuration->duration_seconds;

                                $minutes = floor($duration / 60);
                                $seconds = $duration % 60;
                            @endphp

                            <div class="text-2xl font-bold text-primary">
                                {{ $minutes }}m
                                @if ($seconds > 0)
                                    {{ $seconds }}s
                                @endif
                            </div>

                            <p class="text-sm text-primary mt-2 truncate">
                                {{ $longestDuration
                                    ?->workoutExerciseSet
                                    ?->workoutExercise
                                    ?->exercise
                                    ?->name }}
                            </p>

                        @else

                            <p class="text-sm text-muted">
                                {{ __('No record yet') }}
                            </p>

                        @endif

                    </div>


                    {{-- Longest Distance --}}
                    <div class="bg-surface rounded-xl border border-border p-4">

                        <div class="flex items-center gap-2 mb-3">
                            <x-lucide-map-pin
                                class="w-5 h-5 text-secondary"
                            />

                            <span class="text-sm text-muted">
                    {{ __('Longest Distance') }}
                </span>
                        </div>

                        @if ($longestDistance)

                            <div class="text-2xl font-bold text-primary">
                                {{ number_format($longestDistance->distance_km, 2) }}
                                <span class="text-sm font-normal text-muted">
                        km
                    </span>
                            </div>

                            <p class="text-sm text-primary mt-2 truncate">
                                {{ $longestDistance
                                    ?->workoutExerciseSet
                                    ?->workoutExercise
                                    ?->exercise
                                    ?->name }}
                            </p>

                        @else

                            <p class="text-sm text-muted">
                                {{ __('No record yet') }}
                            </p>

                        @endif

                    </div>


                    {{-- Most Calories --}}
                    <div class="bg-surface rounded-xl border border-border p-4">

                        <div class="flex items-center gap-2 mb-3">
                            <x-lucide-flame
                                class="w-5 h-5 text-secondary"
                            />

                            <span class="text-sm text-muted">
                    {{ __('Most Calories') }}
                </span>
                        </div>

                        @if ($mostCalories)

                            <div class="text-2xl font-bold text-primary">
                                {{ number_format($mostCalories->calories_total) }}
                                <span class="text-sm font-normal text-muted">
                        kcal
                    </span>
                            </div>

                            <p class="text-sm text-primary mt-2 truncate">
                                {{ $mostCalories
                                    ?->workoutExerciseSet
                                    ?->workoutExercise
                                    ?->exercise
                                    ?->name }}
                            </p>

                        @else

                            <p class="text-sm text-muted">
                                {{ __('No record yet') }}
                            </p>

                        @endif

                    </div>

                </div>
            </x-card.default>

            <div class="flex flex-col md:flex-row justify-between gap-6 w-full">
                {{--            MY EXERCISES--}}

                <x-card.default
                    variant="outline"
                    class="w-full"
                >

                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-3">

                            <div
                                class="p-2 rounded-lg bg-secondary/10 text-secondary"
                            >

                                <x-lucide-biceps-flexed
                                    class="w-5 h-5"
                                />

                            </div>


                            <div>

                                <h2 class="font-semibold text-primary">
                                    {{ __('My exercises') }}
                                </h2>

                                <p class="text-xs text-muted">
                                    {{ __('Your personal exercises') }}
                                </p>

                            </div>

                        </div>


                        <div
                            class="text-3xl font-bold text-secondary"
                        >
                            {{ $myExercisesCount }}
                        </div>

                    </div>


                    <div class="mt-5">

                        <a
                            href="{{ route('exercises.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-medium text-secondary hover:opacity-80"
                        >

                            {{ __('View exercises') }}

                            <x-lucide-arrow-right
                                class="w-4 h-4"
                            />

                        </a>

                    </div>

                </x-card.default>

                {{-- RECENT WORKOUTS --}}
                <x-card.default
                    variant="outline"
                    class="w-full"
                >

                    {{-- Header --}}
                    <div class="flex items-center gap-3 mb-5">

                        <div
                            class="p-2 rounded-lg bg-secondary/10 text-secondary"
                        >
                            <x-lucide-history
                                class="w-5 h-5"
                            />
                        </div>

                        <div>

                            <h2 class="font-semibold text-primary">
                                {{ __('Recent workouts') }}
                            </h2>

                            <p class="text-xs text-muted">
                                {{ __('Your latest training sessions') }}
                            </p>

                        </div>

                    </div>


                    @if ($recentWorkouts->isNotEmpty())

                        <div class="space-y-2">

                            @foreach ($recentWorkouts as $session)

                                <a
                                    href="{{ route('sessions.summary', $session) }}"
                                    class="flex items-center justify-between gap-3 p-3 rounded-xl bg-surface-hover/50 hover:bg-surface-hover transition"
                                >

                                    {{-- Left --}}
                                    <div class="flex items-center gap-3 min-w-0">

                                        <div
                                            class="w-10 h-10 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center shrink-0"
                                        >
                                            <x-lucide-dumbbell
                                                class="w-5 h-5"
                                            />
                                        </div>


                                        <div class="min-w-0">

                                            {{-- Workout Name --}}
                                            <p
                                                class="font-medium text-sm text-primary truncate"
                                            >
                                                {{ $session->workout->name }}
                                            </p>


                                            {{-- Date --}}
                                            <p
                                                class="text-xs text-muted mt-0.5"
                                            >
                                                {{ $session->completed_at->diffForHumans() }}
                                            </p>

                                        </div>

                                    </div>


                                    {{-- Right --}}
                                    <div
                                        class="flex items-center gap-3 shrink-0"
                                    >

                                        {{-- Duration --}}
                                        <div
                                            class="text-right hidden sm:block"
                                        >

                                            <p
                                                class="text-xs text-muted"
                                            >
                                                {{ __('Duration') }}
                                            </p>

                                            <p
                                                class="text-sm font-semibold text-primary"
                                            >
                                                {{ $session->formatted_duration }}
                                            </p>

                                        </div>


                                        {{-- Sets --}}
                                        <div
                                            class="text-right hidden md:block"
                                        >

                                            <p
                                                class="text-xs text-muted"
                                            >
                                                {{ __('Sets') }}
                                            </p>

                                            <p
                                                class="text-sm font-semibold text-primary"
                                            >
                                                {{ $session->completed_sets_count }}
                                            </p>

                                        </div>


                                        <x-lucide-chevron-right
                                            class="w-4 h-4 text-muted"
                                        />

                                    </div>

                                </a>

                            @endforeach

                        </div>

                    @else

                        {{-- Empty state --}}
                        <div class="py-8 text-center">

                            <div
                                class="w-12 h-12 mx-auto mb-3 rounded-xl bg-surface-hover flex items-center justify-center"
                            >

                                <x-lucide-history
                                    class="w-6 h-6 text-muted"
                                />

                            </div>


                            <p class="text-sm font-medium text-primary">
                                {{ __('No recent workouts') }}
                            </p>


                            <p class="text-xs text-muted mt-1">
                                {{ __('Complete a workout to see your history here.') }}
                            </p>

                        </div>

                    @endif

                </x-card.default>
            </div>
        </div>
    </div>
</x-app-layout>
