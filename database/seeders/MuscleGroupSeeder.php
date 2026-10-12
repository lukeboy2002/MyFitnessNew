<?php

namespace Database\Seeders;

use App\Models\BodyPart;
use App\Models\MuscleGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MuscleGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [

            'Neck' => [
                'Neck',
            ],

            'Shoulders' => [
                'Front Delts',
                'Side Delts',
                'Rear Delts',
                'Rotator Cuff',
            ],

            // 'Pectorals' is samengevoegd met 'Chest' (waren dubbelop)
            'Chest' => [
                'Chest',
            ],

            'Back' => [
                'Upper Back',
                'Traps',
                'Rhomboids',
                'Lats',
                'Lower Back',
            ],

            'Arms' => [
                'Biceps',
                'Triceps',
                'Forearm Flexors',
                'Forearm Extensors',
            ],

            'Core' => [
                'Abs',
                'Obliques',
                'Serratus Anterior',
            ],

            'Glutes' => [
                'Glutes',
                'Hip Flexors',
            ],

            'Legs' => [
                'Quadriceps',
                'Hamstrings',
                'Adductors',
                'Abductors',
                'Calves',
                'Tibialis Anterior',
                'Peroneals',
            ],

            'Cardio' => [
                'Cardio',
            ],
        ];

        foreach ($groups as $bodyPart => $muscleGroups) {

            $bodyPartModel = BodyPart::where('name', $bodyPart)->firstOrFail();

            foreach ($muscleGroups as $group) {

                MuscleGroup::updateOrCreate(
                    [
                        'slug' => Str::slug($group),
                    ],
                    [
                        'body_part_id' => $bodyPartModel->id,
                        'name' => $group,
                        'image_path' => 'musclegroups/'.Str::slug($group).'.png',
                    ]
                );
            }
        }
    }
}
