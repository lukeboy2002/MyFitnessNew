<?php

use App\Livewire\Musclegroups\MusclegroupIndex;
use App\Models\BodyPart;
use App\Models\MuscleGroup;
use App\Models\User;
use Livewire\Livewire;

test('musclegroup index component renders correctly', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(MusclegroupIndex::class)
        ->assertStatus(200);
});

test('musclegroup index can be filtered by body part', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $bodyPart1 = BodyPart::factory()->create(['name' => 'Upper Body']);
    $bodyPart2 = BodyPart::factory()->create(['name' => 'Lower Body']);

    $muscleGroup1 = MuscleGroup::factory()->create([
        'name' => 'Chest',
        'body_part_id' => $bodyPart1->id,
    ]);

    $muscleGroup2 = MuscleGroup::factory()->create([
        'name' => 'Quads',
        'body_part_id' => $bodyPart2->id,
    ]);

    Livewire::test(MusclegroupIndex::class)
        ->assertSee('Chest')
        ->assertSee('Quads')
        ->set('bodyPartId', $bodyPart1->id)
        ->assertSee('Chest')
        ->assertDontSee('Quads')
        ->set('bodyPartId', $bodyPart2->id)
        ->assertDontSee('Chest')
        ->assertSee('Quads')
        ->set('bodyPartId', '')
        ->assertSee('Chest')
        ->assertSee('Quads');
});
