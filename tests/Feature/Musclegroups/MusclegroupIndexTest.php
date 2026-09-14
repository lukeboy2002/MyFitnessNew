<?php

use App\Livewire\Musclegroups\MusclegroupIndex;
use App\Models\BodyPart;
use App\Models\MuscleGroup;
use App\Models\User;
use Livewire\Livewire;

test('musclegroup index displays associated bodypart for musclegroups', function () {
    $user = User::factory()->create();
    $bodyPart = BodyPart::factory()->create(['name' => 'Upper Body']);
    $muscleGroup = MuscleGroup::factory()->create([
        'name' => 'Chest',
        'body_part_id' => $bodyPart->id,
    ]);

    Livewire::actingAs($user)
        ->test(MusclegroupIndex::class)
        ->assertSee('Chest')
        ->assertSee('Upper Body');
});

test('musclegroups can be filtered by bodypart', function () {
    $user = User::factory()->create();
    $bodyPart1 = BodyPart::factory()->create(['name' => 'Upper Body']);
    $bodyPart2 = BodyPart::factory()->create(['name' => 'Lower Body']);

    $chest = MuscleGroup::factory()->create([
        'name' => 'Chest',
        'body_part_id' => $bodyPart1->id,
    ]);

    $legs = MuscleGroup::factory()->create([
        'name' => 'Quads',
        'body_part_id' => $bodyPart2->id,
    ]);

    Livewire::actingAs($user)
        ->test(MusclegroupIndex::class)
        ->assertSee('Chest')
        ->assertSee('Quads')
        ->set('bodyPartFilter', (string) $bodyPart1->id)
        ->assertSee('Chest')
        ->assertDontSee('Quads')
        ->set('bodyPartFilter', (string) $bodyPart2->id)
        ->assertDontSee('Chest')
        ->assertSee('Quads')
        ->set('bodyPartFilter', 'all')
        ->assertSee('Chest')
        ->assertSee('Quads');
});

test('musclegroup can be deleted', function () {
    $user = User::factory()->create();
    $bodyPart = BodyPart::factory()->create();
    $muscleGroup = MuscleGroup::factory()->create([
        'body_part_id' => $bodyPart->id,
    ]);

    Livewire::actingAs($user)
        ->test(MusclegroupIndex::class)
        ->call('deleteItem', $muscleGroup->id)
        ->assertDispatched('open-modal', 'delete-musclegroup')
        ->call('confirmDelete')
        ->assertDispatched('close-modal', 'delete-musclegroup')
        ->assertDispatched('musclegroup-deleted');

    expect(MuscleGroup::find($muscleGroup->id))->toBeNull();
});
