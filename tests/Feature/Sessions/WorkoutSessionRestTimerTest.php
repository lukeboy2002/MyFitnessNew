<?php

use App\Enum\ExerciseType;
use App\Enum\WorkoutSetType;
use App\Livewire\Sessions\StrengthExercise;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Livewire\Livewire;

test('completing a strength set dispatches start-rest-timer with the configured rest seconds', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Strength,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    $templateSet = WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
        'target_reps' => 10,
        'target_weight' => 50,
        'rest_seconds' => 90,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
    ]);

    $workoutSet = WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $templateSet->id,
        'completed' => false,
    ]);

    Livewire::actingAs($user)
        ->test(StrengthExercise::class, [
            'session' => $session,
            'workoutExercise' => $workoutExercise,
        ])
        ->set("sets.{$templateSet->id}.reps", 10)
        ->set("sets.{$templateSet->id}.weight", 50)
        ->call('completeSet', $templateSet->id)
        ->assertDispatched('start-rest-timer', seconds: 90);

    expect($workoutSet->fresh()->completed)->toBeTrue();
});

test('completing a strength set with default rest seconds dispatches 60 seconds when null', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Strength,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    $templateSet = WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
        'target_reps' => 10,
        'target_weight' => 50,
        'rest_seconds' => null,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
    ]);

    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $templateSet->id,
        'completed' => false,
    ]);

    Livewire::actingAs($user)
        ->test(StrengthExercise::class, [
            'session' => $session,
            'workoutExercise' => $workoutExercise,
        ])
        ->set("sets.{$templateSet->id}.reps", 10)
        ->set("sets.{$templateSet->id}.weight", 50)
        ->call('completeSet', $templateSet->id)
        ->assertDispatched('start-rest-timer', seconds: 60);
});

test('active workout session view contains the rest timer bar', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Strength,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'rest_seconds' => 75,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('sessions.show', $session));

    $response->assertSuccessful();
    $response->assertSee('restTimer(75)', false);
    $response->assertSee('bottom-14 md:bottom-0', false);
    $response->assertSee('start-rest-timer', false);
});

test('completed workout session view does not contain the rest timer bar', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Strength,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'rest_seconds' => 75,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now()->subHour(),
        'completed_at' => now(),
        'completed' => true,
    ]);

    $response = $this->actingAs($user)->get(route('sessions.show', $session));

    $response->assertSuccessful();
    $response->assertDontSee('restTimer(', false);
});
