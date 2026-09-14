<?php

namespace Database\Factories;

use App\Models\Muscle;
use App\Models\MuscleGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Muscle>
 */
class MuscleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'muscle_group_id' => MuscleGroup::factory(),
            'name' => fake()->unique()->word(),
            'slug' => fake()->unique()->slug(),
        ];
    }
}
