<?php

use App\Livewire\Muscle\MuscleIndex;
use App\Models\BodyPart;
use App\Models\Muscle;
use App\Models\MuscleGroup;
use App\Models\User;
use Livewire\Livewire;

test('muscle index displays list of muscles', function () {
    $user = User::factory()->create();
    $muscle = Muscle::factory()->create(['name' => 'Biceps Brachii']);

    Livewire::actingAs($user)
        ->test(MuscleIndex::class)
        ->assertSee('Biceps Brachii');
});

test('muscles can be searched by name', function () {
    $user = User::factory()->create();
    $biceps = Muscle::factory()->create(['name' => 'Biceps Brachii']);
    $triceps = Muscle::factory()->create(['name' => 'Triceps Brachii']);

    Livewire::actingAs($user)
        ->test(MuscleIndex::class)
        ->assertSee('Biceps Brachii')
        ->assertSee('Triceps Brachii')
        ->set('search', 'Biceps')
        ->assertSee('Biceps Brachii')
        ->assertDontSee('Triceps Brachii')
        ->set('search', 'Triceps')
        ->assertDontSee('Biceps Brachii')
        ->assertSee('Triceps Brachii')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSee('Biceps Brachii')
        ->assertSee('Triceps Brachii');
});

test('muscles can be filtered by musclegroup and bodypart', function () {
    $user = User::factory()->create();
    $bodyPart1 = BodyPart::factory()->create(['name' => 'Upper Body']);
    $bodyPart2 = BodyPart::factory()->create(['name' => 'Lower Body']);

    $arms = MuscleGroup::factory()->create(['body_part_id' => $bodyPart1->id, 'name' => 'Arms']);
    $legs = MuscleGroup::factory()->create(['body_part_id' => $bodyPart2->id, 'name' => 'Legs']);

    $biceps = Muscle::factory()->create(['muscle_group_id' => $arms->id, 'name' => 'Biceps']);
    $quads = Muscle::factory()->create(['muscle_group_id' => $legs->id, 'name' => 'Quadriceps']);

    Livewire::actingAs($user)
        ->test(MuscleIndex::class)
        ->assertSee('Biceps')
        ->assertSee('Quadriceps')
        ->set('muscleGroupFilter', (string) $arms->id)
        ->assertSee('Biceps')
        ->assertDontSee('Quadriceps')
        ->set('muscleGroupFilter', 'all')
        ->set('bodyPartFilter', (string) $bodyPart2->id)
        ->assertDontSee('Biceps')
        ->assertSee('Quadriceps');
});

test('muscle can be deleted', function () {
    $user = User::factory()->create();
    $muscle = Muscle::factory()->create();

    Livewire::actingAs($user)
        ->test(MuscleIndex::class)
        ->call('deleteItem', $muscle->id)
        ->assertDispatched('open-modal', 'delete-muscle')
        ->call('confirmDelete')
        ->assertDispatched('close-modal', 'delete-muscle')
        ->assertDispatched('muscle-deleted');

    expect(Muscle::find($muscle->id))->toBeNull();
});
