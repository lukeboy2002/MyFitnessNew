<?php

namespace Database\Factories;

use App\Models\BodyPart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BodyPart>
 */
class BodyPartFactory extends Factory
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
        ];
    }
}
