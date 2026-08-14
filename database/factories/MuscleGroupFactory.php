<?php

namespace Database\Factories;

use App\Models\BodyPart;
use App\Models\MuscleGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MuscleGroup>
 */
class MuscleGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'body_part_id' => BodyPart::factory(),
        ];
    }
}
