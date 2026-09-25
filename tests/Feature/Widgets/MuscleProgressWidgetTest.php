<?php

use App\Livewire\Muscle\MuscleProgress;
use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Livewire\Livewire;

test('muscle progress widget renders empty state when user has no completed sets', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MuscleProgress::class)
        ->assertSee('Muscle Group Progress')
        ->assertSee('No completed sets recorded in this period.');
});

test('muscle progress widget calculates and displays body part progress for completed workout sets', function () {
    $user = User::factory()->create();

    $chestBodyPart = BodyPart::factory()->create(['name' => 'Chest']);
    $backBodyPart = BodyPart::factory()->create(['name' => 'Back']);

    $benchPress = Exercise::factory()->create(['name' => 'Bench Press', 'user_id' => $user->id]);
    $benchPress->bodyParts()->attach($chestBodyPart->id);

    $pullUp = Exercise::factory()->create(['name' => 'Pull Up', 'user_id' => $user->id]);
    $pullUp->bodyParts()->attach($backBodyPart->id);

    $workout = Workout::factory()->create(['user_id' => $user->id]);

    $weBench = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $benchPress->id,
    ]);

    $wePullUp = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $pullUp->id,
    ]);

    $wesBench1 = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $weBench->id, 'set_number' => 1]);
    $wesBench2 = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $weBench->id, 'set_number' => 2]);
    $wesBench3 = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $weBench->id, 'set_number' => 3]);

    $wesPullUp1 = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $wePullUp->id, 'set_number' => 1]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
        'completed_at' => now(),
        'completed' => true,
    ]);

    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $wesBench1->id,
        'completed' => true,
    ]);
    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $wesBench2->id,
        'completed' => true,
    ]);
    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $wesBench3->id,
        'completed' => true,
    ]);

    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $wesPullUp1->id,
        'completed' => true,
    ]);

    Livewire::actingAs($user)
        ->test(MuscleProgress::class)
        ->assertSee('Chest')
        ->assertSee('3 sets')
        ->assertSee('Back')
        ->assertSee('1 sets')
        ->assertSee('4 sets');
});

test('user can switch periods to filter sets', function () {
    $user = User::factory()->create();

    $chestBodyPart = BodyPart::factory()->create(['name' => 'Chest']);
    $benchPress = Exercise::factory()->create(['name' => 'Bench Press', 'user_id' => $user->id]);
    $benchPress->bodyParts()->attach($chestBodyPart->id);

    $workout = Workout::factory()->create(['user_id' => $user->id]);
    $weBench = WorkoutExercise::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $benchPress->id,
    ]);
    $wesBench = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $weBench->id, 'set_number' => 1]);

    // Session from 2 months ago (in this_year, but not in this_month)
    $oldSession = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now()->subMonths(2)->startOfMonth(),
        'completed_at' => now()->subMonths(2)->startOfMonth()->addHour(),
        'completed' => true,
    ]);

    WorkoutSet::create([
        'workout_session_id' => $oldSession->id,
        'workout_exercise_set_id' => $wesBench->id,
        'completed' => true,
    ]);

    Livewire::actingAs($user)
        ->test(MuscleProgress::class)
        ->set('period', 'this_month')
        ->assertSee('No completed sets recorded in this period.')
        ->call('setPeriod', 'this_year')
        ->assertSee('Chest')
        ->assertSee('1 sets');
});

test('user can expand body part to view muscle group progress breakdown', function () {
    $user = User::factory()->create();

    $chestBodyPart = BodyPart::factory()->create(['name' => 'Chest']);
    $upperChest = MuscleGroup::factory()->create([
        'name' => 'Upper Chest',
        'body_part_id' => $chestBodyPart->id,
    ]);
    $lowerChest = MuscleGroup::factory()->create([
        'name' => 'Lower Chest',
        'body_part_id' => $chestBodyPart->id,
    ]);

    $inclineBench = Exercise::factory()->create(['name' => 'Incline Bench Press', 'user_id' => $user->id]);
    $inclineBench->bodyParts()->attach($chestBodyPart->id);
    $inclineBench->muscleGroups()->attach($upperChest->id);

    $dips = Exercise::factory()->create(['name' => 'Chest Dips', 'user_id' => $user->id]);
    $dips->bodyParts()->attach($chestBodyPart->id);
    $dips->muscleGroups()->attach($lowerChest->id);

    $workout = Workout::factory()->create(['user_id' => $user->id]);

    $weIncline = WorkoutExercise::factory()->create(['workout_id' => $workout->id, 'exercise_id' => $inclineBench->id]);
    $wesIncline = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $weIncline->id, 'set_number' => 1]);

    $weDips = WorkoutExercise::factory()->create(['workout_id' => $workout->id, 'exercise_id' => $dips->id]);
    $wesDips = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $weDips->id, 'set_number' => 1]);

    $session = WorkoutSession::create([
        'user_id' => $user->id,
        'workout_id' => $workout->id,
        'started_at' => now(),
        'completed_at' => now(),
        'completed' => true,
    ]);

    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $wesIncline->id,
        'completed' => true,
    ]);
    WorkoutSet::create([
        'workout_session_id' => $session->id,
        'workout_exercise_set_id' => $wesDips->id,
        'completed' => true,
    ]);

    Livewire::actingAs($user)
        ->test(MuscleProgress::class)
        ->assertSee('Chest')
        ->assertDontSee('Upper Chest')
        ->call('toggleBodyPart', $chestBodyPart->id)
        ->assertSee('Upper Chest')
        ->assertSee('Lower Chest')
        ->call('toggleBodyPart', $chestBodyPart->id)
        ->assertDontSee('Upper Chest');
});

test('muscle progress widget only includes completed sets from current user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $chestBodyPart = BodyPart::factory()->create(['name' => 'Chest']);
    $benchPress = Exercise::factory()->create(['name' => 'Bench Press', 'user_id' => $user1->id]);
    $benchPress->bodyParts()->attach($chestBodyPart->id);

    $workout1 = Workout::factory()->create(['user_id' => $user1->id]);
    $we1 = WorkoutExercise::factory()->create(['workout_id' => $workout1->id, 'exercise_id' => $benchPress->id]);
    $wes1 = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $we1->id, 'set_number' => 1]);

    // User 1 completed set
    $session1 = WorkoutSession::create([
        'user_id' => $user1->id,
        'workout_id' => $workout1->id,
        'started_at' => now(),
        'completed_at' => now(),
        'completed' => true,
    ]);
    WorkoutSet::create([
        'workout_session_id' => $session1->id,
        'workout_exercise_set_id' => $wes1->id,
        'completed' => true,
    ]);

    // User 2 completed set
    $workout2 = Workout::factory()->create(['user_id' => $user2->id]);
    $we2 = WorkoutExercise::factory()->create(['workout_id' => $workout2->id, 'exercise_id' => $benchPress->id]);
    $wes2 = WorkoutExerciseSet::factory()->create(['workout_exercise_id' => $we2->id, 'set_number' => 1]);
    $session2 = WorkoutSession::create([
        'user_id' => $user2->id,
        'workout_id' => $workout2->id,
        'started_at' => now(),
        'completed_at' => now(),
        'completed' => true,
    ]);
    WorkoutSet::create([
        'workout_session_id' => $session2->id,
        'workout_exercise_set_id' => $wes2->id,
        'completed' => true,
    ]);

    // User 1 sees 1 set, not 2
    Livewire::actingAs($user1)
        ->test(MuscleProgress::class)
        ->assertSee('Chest')
        ->assertSee('1 sets');
});

test('dashboard page renders the muscle progress widget successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Muscle Group Progress');
});
