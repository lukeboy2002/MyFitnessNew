<?php

use App\Enum\ExerciseType;
use App\Enum\WorkoutSetType;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;

test('dashboard and personal records index display longest duration for cardio exercises', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $cardioExercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'name' => 'Treadmill Running',
        'type' => ExerciseType::Cardio,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $cardioExercise->id,
    ]);

    $templateSet = WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now()->subHour(),
        'completed_at' => now(),
        'completed' => true,
    ]);

    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $templateSet->id,
        'duration_seconds' => 1500, // 25 min
        'distance_km' => 5.0,
        'completed' => true,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('25m');
    $response->assertSee('Treadmill Running');

    $prResponse = $this->actingAs($user)->get(route('personal-records.index'));

    $prResponse->assertOk();
    $prResponse->assertSee('25 min');
    $prResponse->assertSee('Treadmill Running');
});

test('personal records index formats duration in hours and minutes', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $cardioExercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'name' => 'Long Distance Cycling',
        'type' => ExerciseType::Cardio,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $cardioExercise->id,
    ]);

    $templateSet = WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now()->subHours(2),
        'completed_at' => now(),
        'completed' => true,
    ]);

    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $templateSet->id,
        'duration_seconds' => 4500, // 1h 15m
        'completed' => true,
    ]);

    $prResponse = $this->actingAs($user)->get(route('personal-records.index'));

    $prResponse->assertOk();
    $prResponse->assertSee('1h 15m');
    $prResponse->assertSee('Long Distance Cycling');
});
