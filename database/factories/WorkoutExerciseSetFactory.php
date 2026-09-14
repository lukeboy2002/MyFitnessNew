<?php

namespace Database\Factories;

use App\Enum\WorkoutSetType;
use App\Models\WorkoutExercise;
use App\Models\WorkoutExerciseSet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkoutExerciseSet>
 */
class WorkoutExerciseSetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workout_exercise_id' => WorkoutExercise::factory(),
            'set_number' => 1,
            'type' => WorkoutSetType::Working,
            'target_reps' => 10,
            'target_weight' => 20.00,
            'rest_seconds' => 60,
        ];
    }
}
