<?php

use App\Livewire\Muscles\MuscleForm;
use App\Models\BodyPart;
use App\Models\Muscle;
use App\Models\MuscleGroup;
use App\Models\User;
use Livewire\Livewire;

test('muscle form can create a new muscle', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $bp = BodyPart::query()->create(['name' => 'Upper']);
    $group = MuscleGroup::query()->create([
        'name' => 'Arms',
        'body_part_id' => $bp->id,
    ]);

    Livewire::test(MuscleForm::class)
        ->set('name', 'Biceps')
        ->set('slug', 'biceps')
        ->set('muscle_group_id', $group->id)
        ->call('saveMuscle')
        ->assertRedirect(route('muscles.index'));

    expect(Muscle::where('slug', 'biceps')->where('muscle_group_id', $group->id)->exists())->toBeTrue();
});

test('muscle form can update an existing muscle', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $bp1 = BodyPart::query()->create(['name' => 'Upper']);
    $bp2 = BodyPart::query()->create(['name' => 'Lower']);
    $groupA = MuscleGroup::query()->create(['name' => 'Arms', 'body_part_id' => $bp1->id]);
    $groupB = MuscleGroup::query()->create(['name' => 'Legs', 'body_part_id' => $bp2->id]);

    $muscle = Muscle::query()->create([
        'name' => 'Triceps',
        'slug' => 'triceps',
        'muscle_group_id' => $groupA->id,
    ]);

    Livewire::test(MuscleForm::class, ['muscle' => $muscle])
        ->set('name', 'Triceps Long Head')
        ->set('slug', 'triceps-long-head')
        ->set('muscle_group_id', $groupB->id)
        ->call('saveMuscle')
        ->assertRedirect(route('muscles.index'));

    $muscle->refresh();
    expect($muscle->name)->toBe('Triceps Long Head');
    expect($muscle->slug)->toBe('triceps-long-head');
    expect($muscle->muscle_group_id)->toBe($groupB->id);
});
