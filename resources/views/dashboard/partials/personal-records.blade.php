<a href="{{ route('personal-records.index') }}">
    <x-card.default variant="outline" class="w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-5 relative">
            <div class="flex items-center gap-2">
                <div class="p-2 rounded-lg bg-secondary/10 text-secondary">
                    <x-lucide-trophy class="w-5 h-5"/>
                </div>
                <div>
                    <h2 class="font-semibold text-primary">
                        {{ __('Personal Records') }}
                    </h2>
                    <p class="text-xs text-muted">
                        {{ __('Your best performances') }}
                    </p>
                </div>
            </div>
            <div class="absolute -right-3 -top-3">
                <x-lucide-chevron-up class="h-5 w-5 text-muted rotate-45"/>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Highest Weight --}}
            <x-card.small variant="outline"
                          title="{{ __('Highest Weight') }}"
                          icon="dumbbell"
                          icon_size="5"
                          icon_color="text-secondary">
                @if ($highestWeight)
                    <x-slot:description>
                        {{ number_format($highestWeight->weight, 1) }}
                        <span class="text-sm font-normal text-muted">
                                        kg
                                    </span>
                    </x-slot:description>
                    <x-slot:exercise>
                        {{ $highestWeight
                            ?->workoutExerciseSet
                            ?->workoutExercise
                            ?->exercise
                            ?->name }}
                    </x-slot:exercise>
                @else
                    <x-slot:exercise>
                        {{ __('No record yet') }}
                    </x-slot:exercise>
                @endif
            </x-card.small>
            {{-- Most Reps --}}
            <x-card.small variant="outline"
                          title="{{ __('Most Reps') }}"
                          icon="repeat"
                          icon_size="5"
                          icon_color="text-secondary">
                @if ($mostReps)
                    <x-slot:description>
                        {{ $mostReps->reps }}
                        <span class="text-sm font-normal text-muted">
                                        reps
                                    </span>
                    </x-slot:description>
                    <x-slot:exercise>
                        {{ $mostReps
                            ?->workoutExerciseSet
                            ?->workoutExercise
                            ?->exercise
                            ?->name }}
                    </x-slot:exercise>
                @else
                    <x-slot:exercise>
                        {{ __('No record yet') }}
                    </x-slot:exercise>
                @endif
            </x-card.small>
            {{-- Longest Duration --}}
            <x-card.small variant="outline"
                          title="{{ __('Longest Duration') }}"
                          icon="clock"
                          icon_size="5"
                          icon_color="text-secondary">
                @if ($longestDuration)
                    @php
                        $duration = $longestDuration->duration_seconds;

                        $hours = floor($duration / 3600);
                        $minutes = floor(($duration % 3600) / 60);
                        $seconds = $duration % 60;
                    @endphp

                    <x-slot:description>
                        @if ($hours > 0)
                            {{ $hours }}h {{ $minutes }}m
                        @else
                            {{ $minutes }}m
                            @if ($seconds > 0)
                                {{ $seconds }}s
                            @endif
                        @endif
                    </x-slot:description>
                    <x-slot:exercise>
                        {{ $longestDuration
                            ?->workoutExerciseSet
                            ?->workoutExercise
                            ?->exercise
                            ?->name }}
                    </x-slot:exercise>
                @else
                    <x-slot:exercise>
                        {{ __('No record yet') }}
                    </x-slot:exercise>
                @endif
            </x-card.small>
            {{-- Longest Distance --}}
            <x-card.small variant="outline"
                          title="{{ __('Longest Distance') }}"
                          icon="map-pin"
                          icon_size="5"
                          icon_color="text-secondary">
                @if ($longestDistance)
                    <x-slot:description>
                        {{ number_format($longestDistance->distance_km, 2) }}
                        <span class="text-sm font-normal text-muted">
                                        km
                                    </span>
                    </x-slot:description>
                    <x-slot:exercise>
                        {{ $longestDistance
                            ?->workoutExerciseSet
                            ?->workoutExercise
                            ?->exercise
                            ?->name }}
                    </x-slot:exercise>
                @else
                    <x-slot:exercise>
                        {{ __('No record yet') }}
                    </x-slot:exercise>
                @endif
            </x-card.small>
            {{-- Most Calories --}}
            <x-card.small variant="outline"
                          title="{{ __('Most Calories') }}"
                          icon="flame"
                          icon_size="5"
                          icon_color="text-secondary">
                @if ($mostCalories)
                    <x-slot:description>
                        {{ number_format($mostCalories->calories_total) }}
                        <span class="text-sm font-normal text-muted">
                                        kcal
                                    </span>
                    </x-slot:description>
                    <x-slot:exercise>
                        {{ $mostCalories
                            ?->workoutExerciseSet
                            ?->workoutExercise
                            ?->exercise
                            ?->name }}
                    </x-slot:exercise>
                @else
                    <x-slot:exercise>
                        {{ __('No record yet') }}
                    </x-slot:exercise>
                @endif
            </x-card.small>
        </div>
    </x-card.default>
</a>
