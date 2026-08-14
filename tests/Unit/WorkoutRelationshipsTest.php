<?php

use App\Models\Workout;
use App\Models\WorkoutExercise;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('exposes workoutExercises relation on Workout', function (): void {
    $relation = (new Workout)->workoutExercises();

    expect($relation)->toBeInstanceOf(HasMany::class);
});

it('exposes workout and exercise relations on WorkoutExercise', function (): void {
    $we = new WorkoutExercise;

    expect($we->workout())->toBeInstanceOf(BelongsTo::class)
        ->and($we->exercise())->toBeInstanceOf(BelongsTo::class);
});
