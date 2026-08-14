<?php

use App\Livewire\Exercises\ExerciseIndex;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\User;
use Livewire\Livewire;

it('filters exercises by selected muscle group', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $groupA = MuscleGroup::factory()->create();
    $groupB = MuscleGroup::factory()->create();

    $exA = Exercise::create([
        'user_id' => $user->id,
        'name' => 'Push A',
        'type' => 'strength',
        'description' => null,
        'image_path' => null,
    ]);
    $exA->muscleGroups()->attach($groupA->id);

    $exB = Exercise::create([
        'user_id' => $user->id,
        'name' => 'Run B',
        'type' => 'cardio',
        'description' => null,
        'image_path' => null,
    ]);
    $exB->muscleGroups()->attach($groupB->id);

    Livewire::test(ExerciseIndex::class)
        ->set('muscleGroupId', (string) $groupA->id)
        ->assertSee($exA->name)
        ->assertDontSee($exB->name);
});

it('shows all related muscle groups as badges', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $groupA = MuscleGroup::factory()->create();
    $groupB = MuscleGroup::factory()->create();

    $exercise = Exercise::create([
        'user_id' => $user->id,
        'name' => 'Multi Group Exercise',
        'type' => 'strength',
        'description' => null,
        'image_path' => null,
    ]);
    $exercise->muscleGroups()->attach([$groupA->id, $groupB->id]);

    Livewire::test(ExerciseIndex::class)
        ->assertSee($groupA->name)
        ->assertSee($groupB->name);
});
