<?php

use App\Livewire\Bodyparts\BodypartForm;
use App\Models\BodyPart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('an admin can create a bodypart', function () {
    $admin = User::factory()->create(['is_admin' => true, 'avatar' => null]);

    actingAs($admin);

    Livewire::test(BodypartForm::class)
        ->set('name', 'Chest')
        ->set('slug', 'chest')
        ->call('saveBodyPart')
        ->assertHasNoErrors()
        ->assertRedirect(route('bodyparts.index'));

    expect(BodyPart::where('name', 'Chest')->exists())->toBeTrue();
});

test('slug is automatically generated when name is updated', function () {
    $admin = User::factory()->create(['is_admin' => true, 'avatar' => null]);

    actingAs($admin);

    Livewire::test(BodypartForm::class)
        ->set('name', 'Back Muscles')
        ->assertSet('slug', 'back-muscles');
});

test('validation errors are shown for required fields', function () {
    $admin = User::factory()->create(['is_admin' => true, 'avatar' => null]);

    actingAs($admin);

    Livewire::test(BodypartForm::class)
        ->set('name', '')
        ->set('slug', '')
        ->call('saveBodyPart')
        ->assertHasErrors(['name' => 'required', 'slug' => 'required']);
});
