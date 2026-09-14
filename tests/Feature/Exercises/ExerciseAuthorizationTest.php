<?php

use App\Livewire\Exercises\ExercisesForm;
use App\Livewire\Exercises\ExercisesIndex;
use App\Models\Exercise;
use App\Models\User;
use Livewire\Livewire;

test('exercise cards display Custom badge when user is assigned and no badge when global', function () {
    $user = User::factory()->create();
    $customExercise = Exercise::factory()->create([
        'name' => 'Custom Pushup',
        'user_id' => $user->id,
    ]);
    $globalExercise = Exercise::factory()->create([
        'name' => 'Global Pushup',
        'user_id' => null,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->assertSee('Custom Pushup')
        ->assertSee('Global Pushup')
        ->assertSee('Custom');
});

test('user can delete their own added exercise', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'My Exercise',
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->call('deleteItem', $exercise->id)
        ->assertDispatched('open-modal', 'delete-exercice')
        ->call('confirmDelete')
        ->assertDispatched('exercise-deleted');

    expect(Exercise::find($exercise->id))->toBeNull();
});

test('user cannot delete another users exercise', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'Other User Exercise',
        'user_id' => $otherUser->id,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->call('deleteItem', $exercise->id)
        ->assertForbidden();

    expect(Exercise::find($exercise->id))->not->toBeNull();
});

test('user cannot delete global exercise without user', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'Global Exercise',
        'user_id' => null,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->call('deleteItem', $exercise->id)
        ->assertForbidden();

    expect(Exercise::find($exercise->id))->not->toBeNull();
});

test('admin cannot delete user custom exercise', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'User Custom Exercise',
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($admin)
        ->test(ExercisesIndex::class)
        ->call('deleteItem', $exercise->id)
        ->assertForbidden();

    expect(Exercise::find($exercise->id))->not->toBeNull();
});

test('admin can delete global exercise without user', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $exercise = Exercise::factory()->create([
        'name' => 'Global Admin Exercise',
        'user_id' => null,
    ]);

    Livewire::actingAs($admin)
        ->test(ExercisesIndex::class)
        ->call('deleteItem', $exercise->id)
        ->assertDispatched('open-modal', 'delete-exercice')
        ->call('confirmDelete')
        ->assertDispatched('exercise-deleted');

    expect(Exercise::find($exercise->id))->toBeNull();
});

test('user can edit their own added exercise', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'Original Name',
        'user_id' => $user->id,
        'description' => 'Desc',
        'howto' => 'How to do it',
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesForm::class, ['exercise' => $exercise])
        ->set('name', 'Updated Name')
        ->set('description', 'Updated Desc')
        ->set('howto', 'Updated How to')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('exercises.index'));

    expect($exercise->fresh()->name)->toBe('Updated Name');
});

test('user cannot edit another users exercise', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'Other User Name',
        'user_id' => $otherUser->id,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesForm::class, ['exercise' => $exercise])
        ->assertForbidden();
});

test('user cannot edit global exercise without user', function () {
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'Global Exercise',
        'user_id' => null,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesForm::class, ['exercise' => $exercise])
        ->assertForbidden();
});

test('admin cannot edit user custom exercise', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    $exercise = Exercise::factory()->create([
        'name' => 'User Custom Exercise',
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($admin)
        ->test(ExercisesForm::class, ['exercise' => $exercise])
        ->assertForbidden();
});

test('admin can edit global exercise without user', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $exercise = Exercise::factory()->create([
        'name' => 'Global Exercise Admin Edit',
        'user_id' => null,
        'description' => 'Global desc',
        'howto' => 'Global howto',
    ]);

    Livewire::actingAs($admin)
        ->test(ExercisesForm::class, ['exercise' => $exercise])
        ->set('name', 'Global Exercise Edited')
        ->set('description', 'Global desc updated')
        ->set('howto', 'Global howto updated')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('exercises.index'));

    expect($exercise->fresh()->name)->toBe('Global Exercise Edited');
});

test('user creating exercise sets user_id automatically', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ExercisesForm::class)
        ->set('name', 'New Custom Exercise')
        ->set('description', 'New Desc')
        ->set('howto', 'New howto info')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('exercises.index'));

    $created = Exercise::where('name', 'New Custom Exercise')->first();
    expect($created)->not->toBeNull()
        ->and($created->user_id)->toBe($user->id);
});

test('admin creating exercise creates global exercise without user_id', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Livewire::actingAs($admin)
        ->test(ExercisesForm::class)
        ->set('name', 'New Global Admin Exercise')
        ->set('description', 'New Desc')
        ->set('howto', 'New howto info')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('exercises.index'));

    $created = Exercise::where('name', 'New Global Admin Exercise')->first();
    expect($created)->not->toBeNull()
        ->and($created->user_id)->toBeNull();
});

test('exercises index displays edit and delete actions only when authorized', function () {
    $user = User::factory()->create();
    $myExercise = Exercise::factory()->create([
        'name' => 'My Exercise Card',
        'user_id' => $user->id,
    ]);
    $globalExercise = Exercise::factory()->create([
        'name' => 'Global Exercise Card',
        'user_id' => null,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->assertSeeHtml(route('exercises.edit', $myExercise))
        ->assertDontSeeHtml(route('exercises.edit', $globalExercise));
});

test('logged in user only sees their own exercises and global exercises in index', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $myExercise = Exercise::factory()->create([
        'name' => 'User Own Unique Exercise',
        'user_id' => $user->id,
    ]);
    $globalExercise = Exercise::factory()->create([
        'name' => 'Global Unique Exercise',
        'user_id' => null,
    ]);
    $otherUserExercise = Exercise::factory()->create([
        'name' => 'Other User Hidden Exercise',
        'user_id' => $otherUser->id,
    ]);

    Livewire::actingAs($user)
        ->test(ExercisesIndex::class)
        ->assertSee('User Own Unique Exercise')
        ->assertSee('Global Unique Exercise')
        ->assertDontSee('Other User Hidden Exercise');
});

test('admin only sees global exercises and cannot see any user exercises in index', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();

    $globalExercise = Exercise::factory()->create([
        'name' => 'Global Admin Visible Exercise',
        'user_id' => null,
    ]);
    $userExercise = Exercise::factory()->create([
        'name' => 'User Custom Exercise Secret',
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($admin)
        ->test(ExercisesIndex::class)
        ->assertSee('Global Admin Visible Exercise')
        ->assertDontSee('User Custom Exercise Secret');
});

test('user cannot view another users exercise details', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $otherUserExercise = Exercise::factory()->create([
        'name' => 'Secret Other Exercise',
        'user_id' => $otherUser->id,
    ]);

    $this->actingAs($user)
        ->get(route('exercises.show', $otherUserExercise))
        ->assertForbidden();
});

test('admin cannot view users custom exercise details', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();

    $userExercise = Exercise::factory()->create([
        'name' => 'User Exercise Show Secret',
        'user_id' => $user->id,
    ]);

    $this->actingAs($admin)
        ->get(route('exercises.show', $userExercise))
        ->assertForbidden();
});

test('user and admin can view global exercise details', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();

    $globalExercise = Exercise::factory()->create([
        'name' => 'Global Public Exercise',
        'user_id' => null,
    ]);

    $this->actingAs($user)
        ->get(route('exercises.show', $globalExercise))
        ->assertSuccessful();

    $this->actingAs($admin)
        ->get(route('exercises.show', $globalExercise))
        ->assertSuccessful();
});
