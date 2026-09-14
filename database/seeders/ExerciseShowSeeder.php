<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExerciseShowSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->skip(1)->first();

        if (! $user) {
            return;
        }

        $exercise = Exercise::query()
            ->where('name', 'Pec Fly Machine')
            ->first();

        if (! $exercise) {
            return;
        }

        /*
         * Demo workout
         */
        $workout = Workout::create([
            'user_id' => $user->id,
            'name' => 'Demo Strength Workout',
            'slug' => 'demo-strength-workout',
        ]);

        /*
         * Exercise in workout
         */
        $workoutExercise = WorkoutExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exercise->id,
            'order' => 1,
        ]);

        /*
         * Planned sets
         */
        for ($setNumber = 1; $setNumber <= 4; $setNumber++) {
            WorkoutExerciseSet::create([
                'workout_exercise_id' => $workoutExercise->id,
                'set_number' => $setNumber,
                'type' => 'working',
                'target_reps' => 10,
                'target_weight' => 80,
                'rest_seconds' => 60,
            ]);
        }

        /*
         * Six historical sessions
         */
        $workouts = [
            [
                'days_ago' => 35,
                'sets' => [
                    [70, 10],
                    [70, 10],
                    [70, 8],
                    [70, 8],
                ],
            ],
            [
                'days_ago' => 28,
                'sets' => [
                    [72.5, 10],
                    [72.5, 10],
                    [72.5, 9],
                    [72.5, 8],
                ],
            ],
            [
                'days_ago' => 21,
                'sets' => [
                    [75, 10],
                    [75, 10],
                    [75, 10],
                    [75, 8],
                ],
            ],
            [
                'days_ago' => 14,
                'sets' => [
                    [77.5, 10],
                    [77.5, 10],
                    [77.5, 9],
                    [77.5, 8],
                ],
            ],
            [
                'days_ago' => 7,
                'sets' => [
                    [80, 10],
                    [80, 10],
                    [80, 9],
                    [80, 8],
                ],
            ],
            [
                'days_ago' => 0,
                'sets' => [
                    [82.5, 10],
                    [82.5, 10],
                    [82.5, 8],
                    [82.5, 8],
                ],
            ],
        ];

        foreach ($workouts as $demo) {

            $completedAt = Carbon::now()
                ->subDays($demo['days_ago'])
                ->setTime(18, 30);

            $session = WorkoutSession::create([
                'user_id' => $user->id,
                'workout_id' => $workout->id,
                'started_at' => $completedAt->copy()->subHour(),
                'completed_at' => $completedAt,
                'completed' => true,
            ]);

            foreach ($demo['sets'] as $index => [$weight, $reps]) {

                $templateSet = $workoutExercise
                    ->workoutExerciseSets()
                    ->where('set_number', $index + 1)
                    ->first();

                WorkoutSet::create([
                    'workout_session_id' => $session->id,
                    'workout_exercise_set_id' => $templateSet->id,

                    'weight' => $weight,
                    'reps' => $reps,

                    'completed' => true,
                ]);
            }
        }
    }
}
