@use('App\Enum\ExerciseType')

<div class="space-y-4">

    {{-- Add Exercise --}}
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

    {{-- Exercises --}}
    @foreach($sessionExercises as $sessionExercise)
        @php
            $workoutExercise = $sessionExercise->workoutExercise;
        @endphp
        @if($workoutExercise->exercise->type === ExerciseType::Strength)

            <livewire:sessions.strength-exercise
                :session="$session"
                :workout-exercise="$workoutExercise"
                :key="'free-strength-' . $sessionExercise->id"
            />
        @elseif($workoutExercise->exercise->type === ExerciseType::Cardio)
            <livewire:sessions.cardio-exercise
                :session="$session"
                :workout-exercise="$workoutExercise"
                :key="'free-cardio-' . $sessionExercise->id"
            />
        @endif
    @endforeach

    {{-- Existing Add Exercise modal --}}
    @include('livewire.workout.partials.add-exercise-modal')
</div>
