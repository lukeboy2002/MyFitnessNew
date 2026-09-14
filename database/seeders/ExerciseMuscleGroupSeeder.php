<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Database\Seeder;

class ExerciseMuscleGroupSeeder extends Seeder
{
    public function run(): void
    {
        $relations = [

            'pec-fly-machine' => [
                'pectorals',
            ],

            'rear-delt-machine' => [
                'rear-delts',
            ],

            'seated-row' => [
                'upper-back',
                'rhomboids',
                'lats',
                'biceps',
            ],

            'pullover-machine' => [
                'lats',
                'pectorals',
            ],

            'shoulder-press' => [
                'front-delts',
                'side-delts',
                'triceps',
            ],

            'chest-press-machine' => [
                'pectorals',
                'front-delts',
                'triceps',
            ],

            'deltoid-raise' => [
                'side-delts',
            ],

            'back-extension-machine' => [
                'lower-back',
                'glutes',
            ],

            'ab-crush-machine' => [
                'abs',
            ],

            'seated-triceps-press' => [
                'triceps',
            ],

            'bilateral-arm-curl-machine' => [
                'biceps',
            ],

            'biceps-curl-cable-station' => [
                'biceps',
            ],

            'leg-press-machine' => [
                'quadriceps',
                'glutes',
                'hamstrings',
            ],

            'seated-leg-curl' => [
                'hamstrings',
            ],

            'leg-extension-machine' => [
                'quadriceps',
            ],

            'abduction' => [
                'abductors',
            ],

            'adduction' => [
                'adductors',
            ],

            'low-row-v-grip' => [
                'upper-back',
                'rhomboids',
                'lats',
                'biceps',
            ],

            'high-row-plated' => [
                'upper-back',
                'rhomboids',
                'traps',
                'rear-delts',
                'biceps',
            ],

            'lat-pull-down-cable' => [
                'lats',
                'upper-back',
                'biceps',
            ],

            'lunge-multi-station' => [
                'quadriceps',
                'hamstrings',
                'glutes',
                'hip-flexors',
            ],

            'torso-twist-multi-station' => [
                'obliques',
            ],

            'squat-multi-station' => [
                'quadriceps',
                'hamstrings',
                'glutes',
                'hip-flexors',
            ],

            'push-press-multi-station' => [
                'front-delts',
                'side-delts',
                'triceps',
            ],

            'push-down-multi-station' => [
                'triceps',
            ],

            'hip-glute-press-multi-station' => [
                'glutes',
                'hip-flexors',
            ],

            'glute-drive-plated' => [
                'glutes',
                'hamstrings',
            ],

            // Cardio
            'stair-machine' => ['cardio'],
            'rowing' => ['cardio'],
            'treadmill' => ['cardio'],
            'cross-trainer' => ['cardio'],
            'hometrainer' => ['cardio'],
            'hiit-bike' => ['cardio'],
            'ski' => ['cardio'],
            'hitt-mill' => ['cardio'],

            'seated-palms-up-wrist-curl' => [
                'forearm-flexors',
            ],

            'tilt-seat-calf-plated' => [
                'calves',
            ],

            'chest-press-cable-station' => [
                'pectorals',
                'front-delts',
                'triceps',
            ],

            'ab-adduction-cable-station' => [
                'adductors',
                'abductors',
            ],

            'ab-crunch-cable-station' => [
                'abs',
            ],

            'shoulder-press-plated' => [
                'front-delts',
                'side-delts',
                'triceps',
            ],

            'leg-press-plated' => [
                'quadriceps',
                'glutes',
                'hamstrings',
            ],

            'row-plated' => [
                'upper-back',
                'rhomboids',
                'lats',
                'biceps',
            ],

            'incline-press-plated' => [
                'pectorals',
                'front-delts',
                'triceps',
            ],

            'pulldown-multi-station' => [
                'lats',
                'upper-back',
                'biceps',
            ],

            'shoulder-press-multi-station' => [
                'front-delts',
                'side-delts',
                'triceps',
            ],

            'biceps-curl-multi-station' => [
                'biceps',
            ],

            'ab-crunch-multi-station' => [
                'abs',
            ],

            'chest-press-multi-station' => [
                'pectorals',
                'front-delts',
                'triceps',
            ],

            'high-row-multi-station' => [
                'upper-back',
                'rhomboids',
                'traps',
                'rear-delts',
                'biceps',
            ],

            'triceps-press-cable-station' => [
                'triceps',
            ],

            'triceps-kickback-dumbbell' => [
                'triceps',
            ],
        ];

        foreach ($relations as $exerciseSlug => $muscleGroupSlugs) {
            $exercise = Exercise::where('slug', $exerciseSlug)->firstOrFail();

            $muscleGroupIds = MuscleGroup::whereIn('slug', $muscleGroupSlugs)
                ->pluck('id');

            $exercise->muscleGroups()->sync($muscleGroupIds);
        }
    }
}
