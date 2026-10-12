<?php

use App\Enum\ExerciseType;
use App\Livewire\Exercises\ExercisesForm;
use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\User;
use Livewire\Livewire;

test('exercises form can create an exercise with body parts', function () {
    $user = User::factory()->create();
    $bodyParts = BodyPart::factory()->count(2)->create();

    Livewire::actingAs($user)
        ->test(ExercisesForm::class)
        ->set('name', 'Bench Press')
        ->set('type', ExerciseType::Strength->value)
        ->set('description', 'Chest workout')
        ->set('howto', 'Lie down and push')
        ->set('selectedBodyParts', $bodyParts->pluck('id')->all())
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('exercises.index'));

    $exercise = Exercise::where('name', 'Bench Press')->first();
    expect($exercise)->not->toBeNull()
        ->and($exercise->bodyParts)->toHaveCount(2)
        ->and($exercise->bodyParts->pluck('id')->all())->toEqualCanonicalizing($bodyParts->pluck('id')->all());
});

test('exercises form loads existing body parts when editing an exercise', function () {
    $user = User::factory()->create();
    $bodyParts = BodyPart::factory()->count(2)->create();

    $exercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'type' => ExerciseType::Strength,
    ]);
    $exercise->bodyParts()->sync($bodyParts->pluck('id')->all());

    Livewire::actingAs($user)
        ->test(ExercisesForm::class, ['exercise' => $exercise])
        ->assertSet('selectedBodyParts', $bodyParts->pluck('id')->all());
});

test('exercises form updates body parts when saving edited exercise', function () {
    $user = User::factory()->create();
    $initialBodyParts = BodyPart::factory()->count(2)->create();
    $newBodyPart = BodyPart::factory()->create();

    $exercise = Exercise::factory()->create([
        'user_id' => $user->id,
        'type' => ExerciseType::Strength,
        'description' => 'Original desc',
        'howto' => 'Original howto',
    ]);
    $exercise->bodyParts()->sync($initialBodyParts->pluck('id')->all());

    Livewire::actingAs($user)
        ->test(ExercisesForm::class, ['exercise' => $exercise])
        ->set('selectedBodyParts', [$newBodyPart->id])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('exercises.index'));

    expect($exercise->fresh()->bodyParts)->toHaveCount(1)
        ->and($exercise->fresh()->bodyParts->first()->id)->toBe($newBodyPart->id);
});

test('exercises form renders body parts and muscle groups options in view', function () {
    $user = User::factory()->create();
    $bodyPart = BodyPart::factory()->create(['name' => 'Upper Body']);
    $muscleGroup = MuscleGroup::factory()->create(['name' => 'Chest']);

    Livewire::actingAs($user)
        ->test(ExercisesForm::class)
        ->assertSee('Upper Body')
        ->assertSee('Chest');
});
