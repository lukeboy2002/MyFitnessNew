<?php

use App\Models\BodyPart;
use App\Models\Muscle;
use App\Models\MuscleGroup;

test('muscles.edit route generates correct URL with muscle parameter', function () {
    $bp = BodyPart::query()->create(['name' => 'Upper']);
    $group = MuscleGroup::query()->create(['name' => 'Arms', 'body_part_id' => $bp->id]);

    $muscle = Muscle::query()->create([
        'name' => 'Biceps',
        'slug' => 'biceps',
        'muscle_group_id' => $group->id,
    ]);

    $url = route('muscles.edit', $muscle);

    expect($url)->toContain("/muscles/{$muscle->id}/edit");
});
