@if($lastWorkout)

    <x-card.default variant="outline"
                    class="w-full">
        {{-- Header --}}
        <a href="{{ route('sessions.summary', $lastWorkout) }}">
            <div class="flex items-center justify-between relative">
                <div class="flex items-center gap-2">
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
                <div class="absolute -right-3 -top-3">
                    <x-lucide-chevron-up class="h-5 w-5 text-muted rotate-45"/>
                </div>
            </div>

            <div class="mb-5">
                <h3 class="text-lg font-semibold text-primary">
                    {{ $lastWorkout->workout->name }}
                </h3>
                <p class="text-xs text-muted mt-1">
                    {{ $lastWorkout->completed_at->diffForHumans() }}
                </p>
            </div>
            {{-- Statistics --}}
            <div class="grid grid-cols-3 gap-3">
                {{-- Duration --}}
                <x-card.small variant="outline"
                              icon="clock"
                              title="{{ __('Duration') }}">
                    <x-slot:description>
                        {{ $lastWorkout->formatted_duration }}
                    </x-slot:description>
                </x-card.small>
                {{-- Exercises --}}
                <x-card.small variant="outline"
                              icon="dumbbell"
                              title="{{ __('Exercises') }}">
                    <x-slot:description>
                        {{ $lastWorkout
                            ->workout
                            ->workoutExercises
                            ->count() }}
                    </x-slot:description>
                </x-card.small>
                {{-- Completed Sets --}}
                <x-card.small variant="outline"
                              icon="check"
                              title="{{ __('Sets') }}">
                    <x-slot:description>
                        {{ $lastWorkout
                            ->workoutSets
                            ->where('completed', true)
                            ->count() }}
                    </x-slot:description>
                </x-card.small>
            </div>
        </a>
    </x-card.default>

@else
    <x-card.default variant="outline"
                    class="w-full">
        {{-- Header --}}
        <div class="flex items-center gap-2">
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
        <div class="py-8 text-center">
            <div
                class="w-12 h-12 mx-auto mb-3 rounded-xl bg-surface-hover flex items-center justify-center">
                <x-lucide-dumbbell
                    class="w-6 h-6 text-muted"/>
            </div>
            <p class="text-sm font-medium text-primary">
                {{ __('No workouts yet') }}
            </p>
            <p class="text-xs text-muted mt-1">
                {{ __('Complete your first workout to see it here.') }}
            </p>
        </div>
    </x-card.default>
@endif
