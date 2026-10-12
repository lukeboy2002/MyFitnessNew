<?php

use App\Enum\MuscleRole;
use App\Livewire\Exercises\ExercisesShow;
use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\User;
use Livewire\Livewire;

test('overview displays muscles sorted by primary, secondary, then stabilizer role', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create();

    $stabilizerMuscle = Muscle::factory()->create(['name' => 'Stabilizer Muscle']);
    $primaryMuscle = Muscle::factory()->create(['name' => 'Primary Muscle']);
    $secondaryMuscle = Muscle::factory()->create(['name' => 'Secondary Muscle']);

    // Attach in non-sorted order
    $exercise->muscles()->attach($stabilizerMuscle->id, ['role' => MuscleRole::Stabilizer->value]);
    $exercise->muscles()->attach($secondaryMuscle->id, ['role' => MuscleRole::Secondary->value]);
    $exercise->muscles()->attach($primaryMuscle->id, ['role' => MuscleRole::Primary->value]);

    $component = Livewire::actingAs($user)
        ->test(ExercisesShow::class, ['exercise' => $exercise]);

    $html = $component->html();

    $primaryPos = strpos($html, 'Primary Muscle');
    $secondaryPos = strpos($html, 'Secondary Muscle');
    $stabilizerPos = strpos($html, 'Stabilizer Muscle');

    expect($primaryPos)->not->toBeFalse()
        ->and($secondaryPos)->not->toBeFalse()
        ->and($stabilizerPos)->not->toBeFalse()
        ->and($primaryPos)->toBeLessThan($secondaryPos)
        ->and($secondaryPos)->toBeLessThan($stabilizerPos);
});

test('how to tab displays instructions when exercise has howto', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'howto' => '<p><span style="font-weight: bold;">Instelling en Uitvoering</span></p>',
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesShow::class, ['exercise' => $exercise])
        ->set('tab', 'how-to')
        ->assertSeeHtml('Instelling en Uitvoering')
        ->assertSeeHtml('prose prose-orange');
});

test('how to tab displays empty message when exercise has no howto', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'howto' => null,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesShow::class, ['exercise' => $exercise])
        ->set('tab', 'how-to')
        ->assertSee('No instructions available for this exercise yet.');
});
