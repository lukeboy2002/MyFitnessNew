<?php

use App\Enum\ExerciseType;
use App\Enum\WorkoutSetType;
use App\Livewire\Sessions\CardioExercise;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Livewire\Livewire;

test('cardio exercise results can be saved without mass assignment exceptions', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Cardio,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    $templateSet = WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
        'target_duration_seconds' => 1800,
        'target_distance_km' => 5.0,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
    ]);

    $component = Livewire::actingAs($user)
        ->test(CardioExercise::class, [
            'session' => $session,
            'workoutExercise' => $workoutExercise,
        ])
        ->set('watts', 180)
        ->set('mets', 8.5)
        ->set('caloriesActive', 250)
        ->set('caloriesTotal', 310)
        ->set('distanceKm', 6.2)
        ->set('pace', '04:50')
        ->set('avgHeartRate', 145)
        ->set('strokeRate', 28)
        ->set('rotations', 85)
        ->set('floors', 12)
        ->call('completeExercise');

    $component->assertHasNoErrors();

    $workoutSet = WorkoutSet::where('workout_session_id', $session->id)
        ->where('workout_exercise_set_id', $templateSet->id)
        ->first();

    expect($workoutSet)->not->toBeNull()
        ->and($workoutSet->completed)->toBeTrue()
        ->and($workoutSet->duration_seconds)->toBe(1800)
        ->and($workoutSet->watts)->toBe(180)
        ->and((float) $workoutSet->mets)->toBe(8.5)
        ->and($workoutSet->calories_active)->toBe(250)
        ->and($workoutSet->calories_total)->toBe(310)
        ->and((float) $workoutSet->distance_km)->toBe(6.2)
        ->and($workoutSet->pace_seconds)->toBe(290)
        ->and($workoutSet->avg_heart_rate)->toBe(145)
        ->and($workoutSet->stroke_rate)->toBe(28)
        ->and($workoutSet->rotations)->toBe(85)
        ->and($workoutSet->floors)->toBe(12);
});

test('cardio exercise initializes duration from template set', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Cardio,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
        'target_duration_seconds' => 1200,
        'target_distance_km' => 3.5,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
    ]);

    $component = Livewire::actingAs($user)
        ->test(CardioExercise::class, [
            'session' => $session,
            'workoutExercise' => $workoutExercise,
        ]);

    expect($component->get('duration'))->toBe('20:00')
        ->and($component->get('distanceKm'))->toBe(3.5);
});

test('cardio exercise accepts various duration formats and saves correct duration_seconds', function (string $input, int $expectedSeconds) {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Cardio,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    $templateSet = WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
        'target_duration_seconds' => null,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(CardioExercise::class, [
            'session' => $session,
            'workoutExercise' => $workoutExercise,
        ])
        ->set('duration', $input)
        ->call('completeExercise');

    $workoutSet = WorkoutSet::where('workout_session_id', $session->id)
        ->where('workout_exercise_set_id', $templateSet->id)
        ->first();

    expect($workoutSet->duration_seconds)->toBe($expectedSeconds);
})->with([
    ['25:30', 1530],
    ['01:15:00', 4500],
    ['45', 2700],
    ['30.5', 1830],
    ['20 min', 1200],
    ['20m', 1200],
    ['1h 20m', 4800],
    ['1u 30m', 5400],
    ['45s', 45],
]);

test('cardio exercise falls back to target duration and distance when completing without manual input', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Cardio,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
    ]);

    $templateSet = WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
        'target_duration_seconds' => 900,
        'target_distance_km' => 2.5,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(CardioExercise::class, [
            'session' => $session,
            'workoutExercise' => $workoutExercise,
        ])
        ->call('completeExercise');

    $workoutSet = WorkoutSet::where('workout_session_id', $session->id)
        ->where('workout_exercise_set_id', $templateSet->id)
        ->first();

    expect($workoutSet->completed)->toBeTrue()
        ->and($workoutSet->duration_seconds)->toBe(900)
        ->and((float) $workoutSet->distance_km)->toBe(2.5);
});
