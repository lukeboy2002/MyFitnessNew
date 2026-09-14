<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Muscle;
use Illuminate\Database\Seeder;

class ExerciseMuscleSeeder extends Seeder
{
    public function run(): void
    {
        $relations = [

            'pec-fly-machine' => [
                'pectoralis-major',
                'serratus-anterior',
                'deltoideus-anterior',
            ],

            'rear-delt-machine' => [
                'deltoideus-posterior',
            ],

            'seated-row' => [
                'latissimus-dorsi',
                'rhomboideus',
                'trapezius',
                'biceps-brachii',
                'brachialis',
            ],

            'pullover-machine' => [
                'latissimus-dorsi',
                'teres-major',
                'pectoralis-major',
            ],

            'shoulder-press' => [
                'deltoideus-anterior',
                'deltoideus-lateral',
                'triceps-brachii',
            ],

            'chest-press-machine' => [
                'pectoralis-major',
                'deltoideus-anterior',
                'triceps-brachii',
            ],

            'deltoid-raise' => [
                'deltoideus-lateral',
            ],

            'back-extension-machine' => [
                'erector-spinae',
                'multifidus',
                'gluteus-maximus',
            ],

            'ab-crush-machine' => [
                'rectus-abdominis',
            ],

            'seated-triceps-press' => [
                'triceps-brachii',
            ],

            'bilateral-arm-curl-machine' => [
                'biceps-brachii',
                'brachialis',
            ],

            'biceps-curl-cable-station' => [
                'biceps-brachii',
                'brachialis',
                'brachioradialis',
            ],

            'leg-press-machine' => [
                'rectus-femoris',
                'vastus-lateralis',
                'vastus-medialis',
                'vastus-intermedius',
                'gluteus-maximus',
                'biceps-femoris',
            ],

            'seated-leg-curl' => [
                'biceps-femoris',
                'semitendinosus',
                'semimembranosus',
            ],

            'leg-extension-machine' => [
                'rectus-femoris',
                'vastus-lateralis',
                'vastus-medialis',
                'vastus-intermedius',
            ],

            'abduction' => [
                'gluteus-medius',
                'gluteus-minimus',
                'tensor-fasciae-latae',
            ],

            'adduction' => [
                'gracilis',
                'pectineus',
                'adductor-longus',
                'adductor-brevis',
                'adductor-magnus',
            ],

            'low-row-v-grip' => [
                'latissimus-dorsi',
                'rhomboideus',
                'trapezius',
                'biceps-brachii',
                'brachialis',
            ],

            'high-row-plated' => [
                'trapezius',
                'rhomboideus',
                'deltoideus-posterior',
                'biceps-brachii',
            ],

            'lat-pull-down-cable' => [
                'latissimus-dorsi',
                'teres-major',
                'biceps-brachii',
                'brachialis',
            ],

            'lunge-multi-station' => [
                'rectus-femoris',
                'vastus-lateralis',
                'vastus-medialis',
                'gluteus-maximus',
                'biceps-femoris',
                'iliopsoas',
            ],

            'torso-twist-multi-station' => [
                'obliquus-externus',
                'obliquus-internus',
            ],

            'squat-multi-station' => [
                'rectus-femoris',
                'vastus-lateralis',
                'vastus-medialis',
                'vastus-intermedius',
                'gluteus-maximus',
                'biceps-femoris',
                'iliopsoas',
            ],

            'push-press-multi-station' => [
                'deltoideus-anterior',
                'deltoideus-lateral',
                'triceps-brachii',
            ],

            'push-down-multi-station' => [
                'triceps-brachii',
            ],

            'hip-glute-press-multi-station' => [
                'gluteus-maximus',
                'gluteus-medius',
                'iliopsoas',
            ],

            'glute-drive-plated' => [
                'gluteus-maximus',
                'gluteus-medius',
                'biceps-femoris',
            ],

            // Cardio
            'stair-machine' => [],
            'rowing' => [],
            'treadmill' => [],
            'cross-trainer' => [],
            'hometrainer' => [],
            'hiit-bike' => [],
            'ski' => [],
            'hitt-mill' => [],

            /*
             * Forearm
             */
            'seated-palms-up-wrist-curl' => [
                'flexor-carpi-radialis',
                'flexor-carpi-ulnaris',
                'palmaris-longus',
                'flexor-digitorum-superficialis',
                'flexor-digitorum-profundus',
            ],

            'tilt-seat-calf-plated' => [
                'gastrocnemius',
                'soleus',
            ],

            'chest-press-cable-station' => [
                'pectoralis-major',
                'deltoideus-anterior',
                'triceps-brachii',
            ],

            'ab-adduction-cable-station' => [
                'gracilis',
                'pectineus',
                'adductor-longus',
                'adductor-brevis',
                'adductor-magnus',
                'gluteus-medius',
                'tensor-fasciae-latae',
            ],

            'ab-crunch-cable-station' => [
                'rectus-abdominis',
            ],

            'shoulder-press-plated' => [
                'deltoideus-anterior',
                'deltoideus-lateral',
                'triceps-brachii',
            ],

            'leg-press-plated' => [
                'rectus-femoris',
                'vastus-lateralis',
                'vastus-medialis',
                'vastus-intermedius',
                'gluteus-maximus',
                'biceps-femoris',
            ],

            'row-plated' => [
                'latissimus-dorsi',
                'rhomboideus',
                'trapezius',
                'biceps-brachii',
            ],

            'incline-press-plated' => [
                'pectoralis-major',
                'deltoideus-anterior',
                'triceps-brachii',
            ],

            'pulldown-multi-station' => [
                'latissimus-dorsi',
                'teres-major',
                'biceps-brachii',
                'brachialis',
            ],

            'shoulder-press-multi-station' => [
                'deltoideus-anterior',
                'deltoideus-lateral',
                'triceps-brachii',
            ],

            'biceps-curl-multi-station' => [
                'biceps-brachii',
                'brachialis',
            ],

            'ab-crunch-multi-station' => [
                'rectus-abdominis',
            ],

            'chest-press-multi-station' => [
                'pectoralis-major',
                'deltoideus-anterior',
                'triceps-brachii',
            ],

            'high-row-multi-station' => [
                'trapezius',
                'rhomboideus',
                'deltoideus-posterior',
                'biceps-brachii',
            ],

            'triceps-press-cable-station' => [
                'triceps-brachii',
            ],

            'triceps-kickback-dumbbell' => [
                'triceps-brachii',
            ],
        ];

        foreach ($relations as $exerciseSlug => $muscleSlugs) {
            $exercise = Exercise::where('slug', $exerciseSlug)->firstOrFail();

            $muscleIds = Muscle::whereIn('slug', $muscleSlugs)
                ->pluck('id');

            $exercise->muscles()->sync($muscleIds);
        }
    }
}
