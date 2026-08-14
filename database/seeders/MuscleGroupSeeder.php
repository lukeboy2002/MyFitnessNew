<?php

namespace Database\Seeders;

use App\Models\BodyPart;
use App\Models\MuscleGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MuscleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //        $groups = [
        //            ['id' => 1, 'body_part_id' => 1, 'name' => 'Neck', 'slug' => 'neck', 'image_path' => 'musclegroups/neck.png'],
        //            ['id' => 2, 'body_part_id' => 2, 'name' => 'Front Delts', 'slug' => 'front-delts', 'image_path' => 'musclegroups/front_delts.png'],
        //            ['id' => 3, 'body_part_id' => 2, 'name' => 'Side Delts', 'slug' => 'side-delts', 'image_path' => 'musclegroups/side_delts.png'],
        //            ['id' => 4, 'body_part_id' => 2, 'name' => 'Rear Delts', 'slug' => 'rear-delts', 'image_path' => 'musclegroups/rear_delts.png'],
        //            ['id' => 5, 'body_part_id' => 2, 'name' => 'Rotator Cuff', 'slug' => 'rotator-cuff', 'image_path' => 'musclegroups/rotator_cuff.png'],
        //            ['id' => 6, 'body_part_id' => 3, 'name' => 'Chest', 'slug' => 'chest', 'image_path' => 'musclegroups/chest.png'],
        //            ['id' => 27, 'body_part_id' => 3, 'name' => 'Pectorals', 'slug' => 'pectorals', 'image_path' => 'musclegroups/pectorals.png'],
        //            ['id' => 7, 'body_part_id' => 4, 'name' => 'Upper Back', 'slug' => 'upper_back', 'image_path' => 'musclegroups/upper_back.png'],
        //            ['id' => 8, 'body_part_id' => 4, 'name' => 'Traps', 'slug' => 'traps', 'image_path' => 'musclegroups/traps.png'],
        //            ['id' => 9, 'body_part_id' => 4, 'name' => 'Rhomboids', 'slug' => 'rhomboids', 'image_path' => 'musclegroups/rhomboids.png'],
        //            ['id' => 10, 'body_part_id' => 4, 'name' => 'Lats', 'slug' => 'lats', 'image_path' => 'musclegroups/lats.png'],
        //            ['id' => 11, 'body_part_id' => 4, 'name' => 'Lower Back', 'slug' => 'lower-back', 'image_path' => 'musclegroups/lower_back.png'],
        //            ['id' => 12, 'body_part_id' => 5, 'name' => 'Biceps', 'slug' => 'biceps', 'image_path' => 'musclegroups/biceps.png'],
        //            ['id' => 13, 'body_part_id' => 5, 'name' => 'Triceps', 'slug' => 'triceps', 'image_path' => 'musclegroups/triceps.png'],
        //            ['id' => 14, 'body_part_id' => 5, 'name' => 'Forearm Flexors', 'slug' => 'forearm-flexors', 'image_path' => 'musclegroups/forearm_flexors.png'],
        //            ['id' => 15, 'body_part_id' => 5, 'name' => 'Forearm Extensors', 'slug' => 'forearm-extensors', 'image_path' => 'musclegroups/forearm_extensors.png'],
        //            ['id' => 16, 'body_part_id' => 6, 'name' => 'Abs', 'slug' => 'abs', 'image_path' => 'musclegroups/abs.png'],
        //            ['id' => 17, 'body_part_id' => 6, 'name' => 'Obliques', 'slug' => 'obliques', 'image_path' => 'musclegroups/obliques.png'],
        //            ['id' => 18, 'body_part_id' => 6, 'name' => 'Serratus Anterior', 'slug' => 'serratus-anterior', 'image_path' => 'musclegroups/serratus_anterior.png'],
        //            ['id' => 19, 'body_part_id' => 7, 'name' => 'Glutes', 'slug' => 'glutes', 'image_path' => 'musclegroups/glutes.png'],
        //            ['id' => 20, 'body_part_id' => 8, 'name' => 'Hip Flexors', 'slug' => 'hip-flexors', 'image_path' => 'musclegroups/hip_flexors.png'],
        //            ['id' => 21, 'body_part_id' => 8, 'name' => 'Quadriceps', 'slug' => 'quadriceps', 'image_path' => 'musclegroups/quadriceps.png'],
        //            ['id' => 22, 'body_part_id' => 8, 'name' => 'Hamstrings', 'slug' => 'hamstrings', 'image_path' => 'musclegroups/hamstrings.png'],
        //            ['id' => 23, 'body_part_id' => 8, 'name' => 'Adductors', 'slug' => 'adductors', 'image_path' => 'musclegroups/adductors.png'],
        //            ['id' => 24, 'body_part_id' => 8, 'name' => 'Abductors', 'slug' => 'abductors', 'image_path' => 'musclegroups/abductors.png'],
        //            ['id' => 25, 'body_part_id' => 8, 'name' => 'Calves', 'slug' => 'calves', 'image_path' => 'musclegroups/calves.png'],
        //            ['id' => 26, 'body_part_id' => 8, 'name' => 'Tibialis Anterior', 'slug' => 'tibialis-anterior', 'image_path' => 'musclegroups/tibialis_anterior.png'],
        //            ['id' => 27, 'body_part_id' => 9, 'name' => 'Cardio', 'slug' => 'cardio'],
        //        ];
        //        foreach ($groups as $group) {
        //            MuscleGroup::updateOrCreate(['id' => $group['id']], $group);
        //        }
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

            'Chest' => [
                'Chest',
                'Pectorals',
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
            ],

            'Cardio' => [
                'Cardio',
            ],
        ];

        foreach ($groups as $bodyPart => $muscleGroups) {

            $body = BodyPart::where('name', $bodyPart)->first();

            foreach ($muscleGroups as $group) {

                MuscleGroup::create([
                    'body_part_id' => $body->id,
                    'name' => $group,
                    'slug' => Str::slug($group),
                    'image_path' => 'musclegroups/'.Str::slug($group).'.png',
                ]);

            }
        }
    }
}
