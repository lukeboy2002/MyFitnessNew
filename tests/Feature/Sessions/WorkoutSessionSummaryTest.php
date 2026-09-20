<?php

use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutSession;

test('summary page renders successfully for a free training session without a workout', function () {
    $user = User::factory()->create();

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => null,
        'started_at' => now()->subHour(),
        'completed_at' => now(),
        'completed' => true,
    ]);

    $response = $this->actingAs($user)->get(route('sessions.summary', $session));

    $response->assertSuccessful();
    $response->assertSee('Free Training');
});

test('summary page renders successfully for a session with a workout', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
        'name' => 'Upper Body Power',
    ]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now()->subHour(),
        'completed_at' => now(),
        'completed' => true,
    ]);

    $response = $this->actingAs($user)->get(route('sessions.summary', $session));

    $response->assertSuccessful();
    $response->assertSee('Upper Body Power');
});
