<?php

namespace Database\Seeders;

use App\Models\BodyPart;
use App\Models\Exercise;
use Illuminate\Database\Seeder;

class ExerciseBodyPartSeeder extends Seeder
{
    public function run(): void
    {
        $relations = [
            'pec-fly-machine' => ['chest'],
            'rear-delt-machine' => ['shoulders'],
            'seated-row' => ['back'],
            'pullover-machine' => ['back'],
            'shoulder-press' => ['shoulders'],
            'chest-press-machine' => ['chest'],
            'deltoid-raise' => ['shoulders'],
            'back-extension-machine' => ['back'],
            'ab-crush-machine' => ['core'],
            'seated-triceps-press' => ['arms'],
            'bilateral-arm-curl-machine' => ['arms'],
            'biceps-curl-cable-station' => ['arms'],
            'leg-press-machine' => ['legs'],
            'seated-leg-curl' => ['legs'],
            'leg-extension-machine' => ['legs'],
            'abduction' => ['legs', 'glutes'],
            'adduction' => ['legs'],
            'low-row-v-grip' => ['back'],
            'high-row-plated' => ['back', 'shoulders'],
            'lat-pull-down-cable' => ['back'],
            'lunge-multi-station' => ['legs', 'glutes'],
            'torso-twist-multi-station' => ['core'],
            'squat-multi-station' => ['legs', 'glutes'],
            'push-press-multi-station' => ['shoulders'],
            'push-down-multi-station' => ['arms'],
            'hip-glute-press-multi-station' => ['glutes'],
            'glute-drive-plated' => ['glutes'],

            // Cardio
            'stair-machine' => ['cardio'],
            'rowing' => ['cardio'],
            'treadmill' => ['cardio'],
            'cross-trainer' => ['cardio'],
            'hometrainer' => ['cardio'],
            'hiit-bike' => ['cardio'],
            'ski' => ['cardio'],
            'hitt-mill' => ['cardio'],

            'seated-palms-up-wrist-curl' => ['arms'],
            'tilt-seat-calf-plated' => ['legs'],

            'chest-press-cable-station' => ['chest'],
            'ab-adduction-cable-station' => ['legs'],
            'ab-crunch-cable-station' => ['core'],
            'shoulder-press-plated' => ['shoulders'],
            'leg-press-plated' => ['legs'],
            'row-plated' => ['back'],
            'incline-press-plated' => ['chest'],
            'pulldown-multi-station' => ['back'],
            'shoulder-press-multi-station' => ['shoulders'],
            'biceps-curl-multi-station' => ['arms'],
            'ab-crunch-multi-station' => ['core'],
            'chest-press-multi-station' => ['chest'],
            'high-row-multi-station' => ['back'],
            'triceps-press-cable-station' => ['arms'],
            'triceps-kickback-dumbbell' => ['arms'],
        ];

        foreach ($relations as $exerciseSlug => $bodyPartSlugs) {
            $exercise = Exercise::where('slug', $exerciseSlug)->firstOrFail();

            $bodyPartIds = BodyPart::whereIn('slug', $bodyPartSlugs)
                ->pluck('id');

            $exercise->bodyParts()->sync($bodyPartIds);
        }
    }
}
