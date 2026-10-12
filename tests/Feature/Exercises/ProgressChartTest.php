<?php

use App\Enum\ExerciseType;
use App\Livewire\Exercises\ProgressChart;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Livewire\Livewire;

test('progress chart mounts successfully for strength exercise with default metric', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Strength,
    ]);

    Livewire::actingAs($user)
        ->test(ProgressChart::class, ['exercise' => $exercise])
        ->assertSet('metric', 'volume')
        ->assertSee(__('Volume'))
        ->assertSee(__('Weight'))
        ->assertSee(__('Reps'))
        ->assertSee(__('1RM'));
});

test('progress chart mounts successfully for cardio exercise without sets and metric is null', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Cardio,
    ]);

    Livewire::actingAs($user)
        ->test(ProgressChart::class, ['exercise' => $exercise])
        ->assertSet('metric', null)
        ->assertSee(__('Not enough data to show progress yet.'));
});

test('progress chart mounts for cardio exercise with completed sets and sets first metric', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Cardio,
    ]);

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $workoutSession = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'completed' => true,
        'completed_at' => now(),
        'started_at' => now(),
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    $workoutExerciseSet = WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
    ]);

    WorkoutSet::create([
        'workout_session_id' => $workoutSession->id,
        'workout_exercise_set_id' => $workoutExerciseSet->id,
        'completed' => true,
        'distance_km' => 5.25,
        'duration_seconds' => 1800,
    ]);

    Livewire::actingAs($user)
        ->test(ProgressChart::class, ['exercise' => $exercise])
        ->assertSet('metric', 'distance')
        ->assertSee(__('Distance'))
        ->assertSee(__('Duration'));
});

test('progress chart can switch metric', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Strength,
    ]);

    Livewire::actingAs($user)
        ->test(ProgressChart::class, ['exercise' => $exercise])
        ->assertSet('metric', 'volume')
        ->call('setMetric', 'weight')
        ->assertSet('metric', 'weight')
        ->call('setMetric', 'invalid_metric')
        ->assertSet('metric', 'weight');
});
