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
        'target_duration_minutes' => 30,
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
        ->call('saveResults');

    $component->assertHasNoErrors();

    $workoutSet = WorkoutSet::where('workout_session_id', $session->id)
        ->where('workout_exercise_set_id', $templateSet->id)
        ->first();

    expect($workoutSet)->not->toBeNull()
        ->and($workoutSet->completed)->toBeTrue()
        ->and($workoutSet->watts)->toBe(180)
        ->and((float) $workoutSet->mets)->toBe(8.5)
        ->and($workoutSet->calories_active)->toBe(250)
        ->and($workoutSet->calories_total)->toBe(310)
        ->and((float) $workoutSet->distance_km)->toBe(6.2)
        ->and($workoutSet->pace)->toBe('04:50')
        ->and($workoutSet->avg_heart_rate)->toBe(145)
        ->and($workoutSet->stroke_rate)->toBe(28)
        ->and($workoutSet->rotations)->toBe(85)
        ->and($workoutSet->floors)->toBe(12);
});
