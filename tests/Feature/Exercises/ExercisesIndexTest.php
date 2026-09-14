<?php

use App\Enum\ExerciseType;
use App\Livewire\Exercises\ExercisesIndex;
use App\Models\Exercise;
use App\Models\User;
use Livewire\Livewire;

test('exercise model correctly identifies cardio and strength types', function () {
    $cardio = Exercise::factory()->create(['type' => ExerciseType::Cardio]);
    $strength = Exercise::factory()->create(['type' => ExerciseType::Strength]);

    expect($cardio->isCardio())->toBeTrue()
        ->and($cardio->isStrength())->toBeFalse()
        ->and($strength->isCardio())->toBeFalse()
        ->and($strength->isStrength())->toBeTrue();
});

test('exercises index displays exercise image when image path is present', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'Bench Press',
        'type' => ExerciseType::Strength,
        'image_path' => 'exercises/bench-press.jpg',
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->assertSee('Bench Press')
        ->assertSee(asset('storage/exercises/bench-press.jpg'));
});

test('exercises index displays heart pulse icon for cardio exercise without image', function () {
    $user = User::factory()->create();
    Exercise::factory()->create([
        'name' => 'Running Outdoors',
        'type' => ExerciseType::Cardio,
        'image_path' => null,
    ]);

    $response = Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->assertSee('Running Outdoors');

    $html = $response->html();
    expect($html)->toContain('M2 9.5a5.5')
        ->and($html)->not->toContain('M12.409 13.017A5');
});

test('exercises index displays biceps flexed icon for strength exercise without image', function () {
    $user = User::factory()->create();
    Exercise::factory()->create([
        'name' => 'Barbell Squat',
        'type' => ExerciseType::Strength,
        'image_path' => null,
    ]);

    $response = Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->assertSee('Barbell Squat');

    $html = $response->html();
    expect($html)->toContain('M12.409 13.017A5')
        ->and($html)->not->toContain('M2 9.5a5.5');
});

test('exercise can be deleted from exercises index by owner', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'Overhead Press',
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->call('deleteItem', $exercise->id)
        ->assertDispatched('open-modal', 'delete-exercice')
        ->call('confirmDelete')
        ->assertDispatched('close-modal', 'delete-exercice')
        ->assertDispatched('exercise-deleted');

    expect(Exercise::find($exercise->id))->toBeNull();
});
