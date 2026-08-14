<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

test('a non-admin user cannot access the bodyparts index', function () {
    $user = User::factory()->create([
        'is_admin' => false,
        'avatar' => null,
    ]);

    actingAs($user)
        ->get(route('bodyparts.index'))
        ->assertStatus(403);
});

test('an admin user can access the bodyparts index', function () {
    $user = User::factory()->create([
        'is_admin' => true,
        'avatar' => null,
    ]);

    actingAs($user)
        ->get(route('bodyparts.index'))
        ->assertStatus(200);
});

test('a guest cannot access the bodyparts index', function () {
    get(route('bodyparts.index'))
        ->assertRedirect(route('login'));
});

test('admin link is visible for admin users on dashboard', function () {
    $user = User::factory()->create(['is_admin' => true, 'avatar' => null]);

    actingAs($user)
        ->get(route('dashboard'))
        ->assertSee(route('bodyparts.index'));
});

test('admin link is not visible for non-admin users on dashboard', function () {
    $user = User::factory()->create(['is_admin' => false, 'avatar' => null]);

    actingAs($user)
        ->get(route('dashboard'))
        ->assertDontSee(route('bodyparts.index'));
});
