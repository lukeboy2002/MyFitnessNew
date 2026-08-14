<?php

use App\Livewire\Muscles\MuscleIndex;
use App\Models\BodyPart;
use App\Models\Muscle;
use App\Models\MuscleGroup;
use App\Models\User;
use Livewire\Livewire;

test('muscle index component renders', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(MuscleIndex::class)
        ->assertStatus(200);
});

test('muscle index can be filtered by muscle group', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $bpA = BodyPart::query()->create(['name' => 'Upper']);
    $bpB = BodyPart::query()->create(['name' => 'Lower']);

    $groupA = MuscleGroup::query()->create(['name' => 'Group A', 'body_part_id' => $bpA->id]);
    $groupB = MuscleGroup::query()->create(['name' => 'Group B', 'body_part_id' => $bpB->id]);

    Muscle::query()->create([
        'name' => 'Muscle One',
        'slug' => 'muscle-one',
        'muscle_group_id' => $groupA->id,
    ]);

    Muscle::query()->create([
        'name' => 'Muscle Two',
        'slug' => 'muscle-two',
        'muscle_group_id' => $groupB->id,
    ]);

    Livewire::test(MuscleIndex::class)
        ->assertSee('Muscle One')
        ->assertSee('Muscle Two')
        ->set('muscleGroupId', (string) $groupA->id)
        ->assertSee('Muscle One')
        ->assertDontSee('Muscle Two')
        ->set('muscleGroupId', (string) $groupB->id)
        ->assertDontSee('Muscle One')
        ->assertSee('Muscle Two')
        ->set('muscleGroupId', '')
        ->assertSee('Muscle One')
        ->assertSee('Muscle Two');
});
