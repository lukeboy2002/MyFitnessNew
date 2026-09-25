<?php

use App\Enum\ExerciseType;
use App\Livewire\Sessions\SessionExercises;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutSession;
use App\Models\WorkoutSessionExercise;
use Livewire\Livewire;

test('session exercises component renders exercises for active workout session', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'type' => ExerciseType::Strength,
        'name' => 'Bench Press',
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
        'order' => 1,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
        'completed' => false,
    ]);

    WorkoutSessionExercise::create([
        'workout_session_id' => $session->id,
        'workout_exercise_id' => $workoutExercise->id,
        'order' => 1,
        'removed' => false,
    ]);

    Livewire::actingAs($user)
        ->test(SessionExercises::class, ['session' => $session])
        ->assertSee('Bench Press');
});

test('can add an exercise to a workout session', function () {
    $user = User::factory()->create();

    $exercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'type' => ExerciseType::Strength,
        'name' => 'Squats',
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => null,
        'started_at' => now(),
        'completed' => false,
    ]);

    Livewire::actingAs($user)
        ->test(SessionExercises::class, ['session' => $session])
        ->call('addExercise', $exercise->id)
        ->assertDispatched('close-modal', 'add-exercise-modal')
        ->assertSee('Squats');

    expect($session->workoutSessionExercises()->where('removed', false)->count())->toBe(1);
});

test('can remove an exercise from a workout session', function () {
    $user = User::factory()->create();

    $exercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'type' => ExerciseType::Strength,
        'name' => 'Deadlift',
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => null,
        'exercise_id' => $exercise->id,
        'order' => 1,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => null,
        'started_at' => now(),
        'completed' => false,
    ]);

    $sessionExercise = WorkoutSessionExercise::create([
        'workout_session_id' => $session->id,
        'workout_exercise_id' => $workoutExercise->id,
        'order' => 1,
        'removed' => false,
    ]);

    Livewire::actingAs($user)
        ->test(SessionExercises::class, ['session' => $session])
        ->call('removeExercise', $sessionExercise->id)
        ->assertDontSeeHtml('strength-'.$sessionExercise->id);

    expect($sessionExercise->fresh()->removed)->toBeTrue()
        ->and($session->workoutSessionExercises()->where('removed', false)->count())->toBe(0);
});

test('re-adding a removed exercise restores it instead of creating a duplicate', function () {
    $user = User::factory()->create();

    $exercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'type' => ExerciseType::Strength,
        'name' => 'Pull Up',
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => null,
        'exercise_id' => $exercise->id,
        'order' => 1,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => null,
        'started_at' => now(),
        'completed' => false,
    ]);

    $sessionExercise = WorkoutSessionExercise::create([
        'workout_session_id' => $session->id,
        'workout_exercise_id' => $workoutExercise->id,
        'order' => 1,
        'removed' => true,
    ]);

    Livewire::actingAs($user)
        ->test(SessionExercises::class, ['session' => $session])
        ->call('addExercise', $exercise->id);

    expect($sessionExercise->fresh()->removed)->toBeFalse()
        ->and($session->workoutSessionExercises()->count())->toBe(1);
});

test('can reorder exercises by moving up and down', function () {
    $user = User::factory()->create();

    $exercise1 = Exercise::factory()->create(['user_id' => $user->id, 'type' => ExerciseType::Strength, 'name' => 'Ex1']);
    $exercise2 = Exercise::factory()->create(['user_id' => $user->id, 'type' => ExerciseType::Strength, 'name' => 'Ex2']);

    $we1 = WorkoutExercise::factory()->create(['workout_id' => null, 'exercise_id' => $exercise1->id, 'order' => 1]);
    $we2 = WorkoutExercise::factory()->create(['workout_id' => null, 'exercise_id' => $exercise2->id, 'order' => 2]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => null,
        'started_at' => now(),
        'completed' => false,
    ]);

    $se1 = WorkoutSessionExercise::create(['workout_session_id' => $session->id, 'workout_exercise_id' => $we1->id, 'order' => 1, 'removed' => false]);
    $se2 = WorkoutSessionExercise::create(['workout_session_id' => $session->id, 'workout_exercise_id' => $we2->id, 'order' => 2, 'removed' => false]);

    Livewire::actingAs($user)
        ->test(SessionExercises::class, ['session' => $session])
        ->call('moveExerciseUp', $se2->id);

    expect($se2->fresh()->order)->toBe(1)
        ->and($se1->fresh()->order)->toBe(2);

    Livewire::actingAs($user)
        ->test(SessionExercises::class, ['session' => $session])
        ->call('moveExerciseDown', $se2->id);

    expect($se2->fresh()->order)->toBe(2)
        ->and($se1->fresh()->order)->toBe(1);
});

test('cannot add an exercise if already actively added', function () {
    $user = User::factory()->create();

    $exercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'type' => ExerciseType::Strength,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => null,
        'exercise_id' => $exercise->id,
        'order' => 1,
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => null,
        'started_at' => now(),
        'completed' => false,
    ]);

    WorkoutSessionExercise::create([
        'workout_session_id' => $session->id,
        'workout_exercise_id' => $workoutExercise->id,
        'order' => 1,
        'removed' => false,
    ]);

    Livewire::actingAs($user)
        ->test(SessionExercises::class, ['session' => $session])
        ->call('addExercise', $exercise->id);

    expect($session->workoutSessionExercises()->where('removed', false)->count())->toBe(1);
});

test('cannot modify another user workout session exercises', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $session = WorkoutSession::create([
        'user_id' => $user1->id,
        'workout_id' => null,
        'started_at' => now(),
        'completed' => false,
    ]);

    Livewire::actingAs($user2)
        ->test(SessionExercises::class, ['session' => $session])
        ->assertForbidden();
});
