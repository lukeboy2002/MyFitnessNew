<?php

namespace Database\Factories;

use App\Enum\ExerciseType;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug()->toString(),
            'type' => fake()->randomElement([ExerciseType::Strength, ExerciseType::Cardio]),
            'description' => fake()->optional()->sentence(),
            'image_path' => null,
        ];
    }
}
