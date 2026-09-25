@use('App\Enum\ExerciseType')

<div class="space-y-4">
    @if(!$session->completed)
        <div class="flex justify-end">
            <x-button.default
                type="button"
                variant="primary"
                icon="plus"
                x-on:click="$dispatch('open-modal', 'add-exercise-modal')"
            >
                {{ __('Add Exercise') }}
            </x-button.default>
        </div>
    @endif

    @if($sessionExercises->isEmpty())
        <div class="p-8 text-center border-2 border-dashed border-border rounded-lg bg-surface/30">
            <x-lucide-dumbbell class="w-12 h-12 text-secondary mx-auto mb-3 opacity-60"/>

            <h3 class="text-base font-medium text-primary mb-1">
                {{ __('No exercises added yet') }}
            </h3>

            <p class="text-xs text-muted mb-4">
                {{ __('Add exercises to this workout to start tracking your sets.') }}
            </p>

            @if(!$session->completed)
                <x-button.default
                    type="button"
                    variant="primary"
                    icon="plus"
                    x-on:click="$dispatch('open-modal', 'add-exercise-modal')"
                >
                    {{ __('Add Exercise') }}
                </x-button.default>
            @endif
        </div>
    @else
        @foreach($sessionExercises as $sessionExercise)
            @php
                $workoutExercise = $sessionExercise->workoutExercise;
            @endphp

            <div class="space-y-1.5" wire:key="session-exercise-{{ $sessionExercise->id }}">
                @if(!$session->completed)
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-muted">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-secondary/15 text-secondary text-xs font-bold">
                                {{ $loop->iteration }}
                            </span>
                            <span>{{ __('Exercise') }} {{ $loop->iteration }}</span>
                        </div>

                        <div class="flex items-center gap-1">
                            <x-button.default
                                type="button"
                                variant="ghost"
                                size="4"
                                icon="chevron-up"
                                title="{{ __('Move Up') }}"
                                wire:click="moveExerciseUp({{ $sessionExercise->id }})"
                                :disabled="$loop->first"
                            />

                            <x-button.default
                                type="button"
                                variant="ghost"
                                size="4"
                                icon="chevron-down"
                                title="{{ __('Move Down') }}"
                                wire:click="moveExerciseDown({{ $sessionExercise->id }})"
                                :disabled="$loop->last"
                            />

                            <x-button.danger
                                type="button"
                                variant="ghost"
                                size="4"
                                icon="trash-2"
                                title="{{ __('Remove Exercise') }}"
                                wire:click="removeExercise({{ $sessionExercise->id }})"
                                wire:confirm="{{ __('Are you sure you want to remove this exercise from this workout?') }}"
                            />
                        </div>
                    </div>
                @endif

                @if($workoutExercise->exercise->type === ExerciseType::Strength)
                    <livewire:sessions.strength-exercise
                        :session="$session"
                        :workout-exercise="$workoutExercise"
                        :key="'strength-' . $sessionExercise->id"
                    />
                @elseif($workoutExercise->exercise->type === ExerciseType::Cardio)
                    <livewire:sessions.cardio-exercise
                        :session="$session"
                        :workout-exercise="$workoutExercise"
                        :key="'cardio-' . $sessionExercise->id"
                    />
                @endif
            </div>
        @endforeach
    @endif

    @if(!$session->completed)
        @include('livewire.workout.partials.add-exercise-modal')
    @endif
</div>
