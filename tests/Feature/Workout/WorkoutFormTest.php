<?php

use App\Enum\ExerciseMetric;
use App\Enum\ExerciseType;
use App\Enum\WorkoutSetType;
use App\Livewire\Workout\WorkoutForm;
use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use Livewire\Livewire;

test('workout create form can be rendered', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(WorkoutForm::class)
        ->assertStatus(200)
        ->assertSee(__('Create Workout'))
        ->assertSee(__('Workout details'));
});

test('user can create a new workout', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test(WorkoutForm::class)
        ->set('name', 'Leg Day Routine')
        ->call('save')
        ->assertHasNoErrors();

    $workout = Workout::where('name', 'Leg Day Routine')->where('user_id', $user->id)->first();
    expect($workout)->not->toBeNull();
    $component->assertRedirect(route('workout.edit', $workout));
});

test('workout edit form renders existing workout with exercises and sets', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
        'name' => 'Upper Body Routine',
    ]);

    $bodyPart = BodyPart::factory()->create();
    $muscleGroup = MuscleGroup::factory()->create();

    $strengthExercise = Exercise::factory()->create([
        'name' => 'Bench Press',
        'type' => ExerciseType::Strength,
    ]);
    $strengthExercise->bodyParts()->attach($bodyPart);
    $strengthExercise->muscleGroups()->attach($muscleGroup);

    $cardioExercise = Exercise::factory()->create([
        'name' => 'Treadmill Run',
        'type' => ExerciseType::Cardio,
    ]);
    $cardioExercise->bodyParts()->attach($bodyPart);
    $cardioExercise->muscleGroups()->attach($muscleGroup);

    $we1 = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $strengthExercise->id,
        'order' => 1,
        'notes' => 'Warm up properly',
    ]);

    $we2 = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $cardioExercise->id,
        'order' => 2,
    ]);

    WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $we1->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
        'target_weight' => 80,
        'target_reps' => 10,
        'rest_seconds' => 90,
    ]);

    WorkoutExerciseSet::factory()->create([
        'workout_exercise_id' => $we2->id,
        'set_number' => 1,
        'type' => WorkoutSetType::Working,
        'target_duration_seconds' => 300,
        'target_metric' => ExerciseMetric::Speed,
        'target_metric_value' => 1.5,
        'target_incline_percent' => 2.0,
    ]);

    Livewire::actingAs($user)
        ->test(WorkoutForm::class, ['workout' => $workout])
        ->assertStatus(200)
        ->assertSee(__('Update Workout'))
        ->assertSee('Bench Press')
        ->assertSee('Treadmill Run')
        ->assertSee('Warm up properly')
        ->assertSee(__('Exercises').' (2)')
        ->assertSee(__('Add exercise'))
        ->assertSee(__('Duration (sec)'))
        ->assertSee(__('Weight (kg)'));
});

test('user can add and remove exercises and sets', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $exercise = Exercise::factory()->create([
        'name' => 'Squat',
        'type' => ExerciseType::Strength,
    ]);

    $component = Livewire::actingAs($user)
        ->test(WorkoutForm::class, ['workout' => $workout])
        ->call('addExercise', $exercise->id)
        ->assertHasNoErrors();

    $we = WorkoutExercise::where('workout_id', $workout->id)->first();
    expect($we)->not->toBeNull();
    expect($we->exercise_id)->toBe($exercise->id);
    expect($we->workoutExerciseSets()->count())->toBe(3);

    // Add set
    $component->call('addSet', $we->id)->assertHasNoErrors();
    expect($we->workoutExerciseSets()->count())->toBe(4);

    $set = $we->workoutExerciseSets()->first();

    // Update set
    $component->call('updateSet', $set->id, 'target_weight', 100)->assertHasNoErrors();
    expect((float) $set->fresh()->target_weight)->toBe(100.0);

    // Update notes
    $component->call('updateExerciseNotes', $we->id, 'Go deep on squats')->assertHasNoErrors();
    expect($we->fresh()->notes)->toBe('Go deep on squats');

    // Remove set
    $component->call('removeSet', $set->id)->assertHasNoErrors();
    expect($we->workoutExerciseSets()->count())->toBe(3);

    // Delete exercise
    $component->call('deleteExercise', $we->id)
        ->call('confirmDelete')
        ->assertHasNoErrors();

    expect(WorkoutExercise::where('workout_id', $workout->id)->count())->toBe(0);
});

test('user can move exercises up and down', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
    ]);

    $ex1 = Exercise::factory()->create(['name' => 'Exercise 1', 'type' => ExerciseType::Strength]);
    $ex2 = Exercise::factory()->create(['name' => 'Exercise 2', 'type' => ExerciseType::Strength]);

    $we1 = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $ex1->id,
        'order' => 1,
    ]);

    $we2 = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $ex2->id,
        'order' => 2,
    ]);

    $component = Livewire::actingAs($user)
        ->test(WorkoutForm::class, ['workout' => $workout]);

    $component->call('moveExerciseDown', $we1->id)->assertHasNoErrors();
    expect($we1->fresh()->order)->toBe(2);
    expect($we2->fresh()->order)->toBe(1);

    $component->call('moveExerciseUp', $we1->id)->assertHasNoErrors();
    expect($we1->fresh()->order)->toBe(1);
    expect($we2->fresh()->order)->toBe(2);
});
