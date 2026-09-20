<?php

namespace App\Services;

use App\Enum\ExerciseType;
use App\Enum\WorkoutSetType;
use App\Models\Exercise;
use App\Models\WorkoutExercise;
use Illuminate\Support\Facades\DB;

class WorkoutExerciseService
{
    public function create(Exercise $exercise, ?int $workoutId, int $order): WorkoutExercise
    {
        return DB::transaction(function () use (
            $exercise,
            $workoutId,
            $order
        ) {
            $workoutExercise = WorkoutExercise::create([
                'workout_id' => $workoutId,
                'exercise_id' => $exercise->id,
                'order' => $order,
            ]);

            $this->createDefaultSets($workoutExercise);

            return $workoutExercise;
        });
    }

    public function createDefaultSets(WorkoutExercise $workoutExercise): void
    {
        if (
            $workoutExercise->exercise->type
            === ExerciseType::Cardio
        ) {
            $workoutExercise
                ->workoutExerciseSets()
                ->create([
                    'set_number' => 1,
                    'type' => WorkoutSetType::Working,

                    // Strength
                    'target_reps' => null,
                    'target_weight' => null,
                    'rest_seconds' => null,

                    // Cardio
                    'target_duration_seconds' => 1200,
                    'target_distance_km' => null,

                    'target_metric' => null,
                    'target_metric_value' => null,

                    'target_incline_percent' => null,
                ]);

            return;
        }

        for ($i = 1; $i <= 3; $i++) {
            $workoutExercise
                ->workoutExerciseSets()
                ->create([
                    'set_number' => $i,
                    'type' => WorkoutSetType::Working,
                    // Strength
                    'target_reps' => 10,
                    'target_weight' => null,
                    'rest_seconds' => 60,
                    // Cardio
                    'target_duration_seconds' => null,
                    'target_distance_km' => null,
                    'target_metric' => null,
                    'target_metric_value' => null,
                    'target_incline_percent' => null,
                ]);
        }
    }
}
