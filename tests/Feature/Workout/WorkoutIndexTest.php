<?php

use App\Enum\ExerciseType;
use App\Enum\WorkoutSetType;
use App\Livewire\Workout\WorkoutIndex;
use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use Livewire\Livewire;

test('archived workout can be restored after viewing details without lazy loading errors', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
        'is_archived' => true,
    ]);

    $bodyPart = BodyPart::factory()->create();
    $muscleGroup = MuscleGroup::factory()->create();

    $exercise1 = Exercise::factory()->create([
        'type' => ExerciseType::Strength,
    ]);
    $exercise1->bodyParts()->attach($bodyPart);
    $exercise1->muscleGroups()->attach($muscleGroup);

    $exercise2 = Exercise::factory()->create([
        'type' => ExerciseType::Cardio,
    ]);
    $exercise2->bodyParts()->attach($bodyPart);
    $exercise2->muscleGroups()->attach($muscleGroup);

    $workoutExercise1 = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise1->id,
        'order' => 1,
    ]);
    $workoutExercise2 = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise2->id,
        'order' => 2,
    ]);

    WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise1->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
    ]);
    WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise2->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
    ]);

    $component = Livewire::actingAs($user)
        ->test(WorkoutIndex::class);

    $component->call('showDetails', $workout->id);

    // Simulate subsequent request where detailWorkout was retained and rehydrated
    $component->set('detailWorkout', Workout::find($workout->id));

    $component->call('confirmToggleArchive', $workout->id, true)
        ->call('toggleArchive');

    $component->html();

    expect($workout->fresh()->is_archived)->toBeFalse();
});

test('active workout can be archived without errors', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
        'is_archived' => false,
    ]);

    $exercise = Exercise::factory()->create([
        'type' => ExerciseType::Strength,
    ]);

    $workoutExercise = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
        'order' => 1,
    ]);

    WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $workoutExercise->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
    ]);

    Livewire::actingAs($user)
        ->test(WorkoutIndex::class)
        ->call('confirmToggleArchive', $workout->id, false)
        ->call('toggleArchive')
        ->assertHasNoErrors();

    expect($workout->fresh()->is_archived)->toBeTrue();
});

test('user cannot view details of workout belonging to another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    Livewire::actingAs($user)
        ->test(WorkoutIndex::class)
        ->call('showDetails', $workout->id)
        ->assertForbidden();
});
