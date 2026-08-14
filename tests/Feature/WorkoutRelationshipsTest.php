<?php

use App\Models\MuscleGroup;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('eager loads muscle groups for workouts without SQL errors', function () {
    $user = User::factory()->create();

    $workout = Workout::create([
        'user_id' => $user->id,
        'name' => 'Upper Body',
        'description' => 'Test',
        'is_archived' => false,
    ]);

    $mg1 = MuscleGroup::factory()->create(['name' => 'Chest']);
    $mg2 = MuscleGroup::factory()->create(['name' => 'Back']);

    // Attach via pivot
    $workout->muscleGroups()->attach([$mg1->id, $mg2->id]);

    // Should not throw and should eager load related models
    $workouts = Workout::with('muscleGroups')
        ->where('user_id', $user->id)
        ->active()
        ->get();

    expect($workouts)->toHaveCount(1);
    expect($workouts->first()->muscleGroups)->pluck('id')->toMatchArray([$mg1->id, $mg2->id]);
});
