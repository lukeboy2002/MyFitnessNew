<?php

namespace Database\Seeders;

use App\Models\BodyPart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BodyPartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bodyParts = [
            'Neck',
            'Shoulders',
            'Chest',
            'Back',
            'Arms',
            'Core',
            'Glutes',
            'Legs',
            'Cardio',
        ];

        foreach ($bodyParts as $name) {
            BodyPart::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'image_path' => 'bodyparts/'.Str::slug($name).'.png',
            ]);
        }
    }
}
