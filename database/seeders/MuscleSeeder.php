<?php

namespace Database\Seeders;

use App\Models\Muscle;
use App\Models\MuscleGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MuscleSeeder extends Seeder
{
    public function run(): void
    {
        $muscles = [

            'Neck' => [
                'Sternocleidomastoideus',
            ],

            'Traps' => [
                'Trapezius',
            ],

            'Upper Back' => [
                'Levator scapulae',
                'Rhomboideus',
            ],

            'Lats' => [
                'Latissimus dorsi',
                'Teres major',
            ],

            'Lower Back' => [
                'Erector spinae',
                'Multifidus',
            ],

            'Biceps' => [
                'Biceps brachii',
                'Brachialis',
            ],

            'Triceps' => [
                'Triceps brachii',
            ],

            'Front Delts' => [
                'Deltoideus (anterior)',
            ],

            'Side Delts' => [
                'Deltoideus (lateral)',
            ],

            'Rear Delts' => [
                'Deltoideus (posterior)',
            ],

            'Rotator Cuff' => [
                'Supraspinatus',
                'Infraspinatus',
                'Teres minor',
                'Subscapularis',
            ],

            'Pectorals' => [
                'Pectoralis major',
                'Pectoralis minor',
            ],

            'Forearm Flexors' => [
                'Brachioradialis',
                'Flexor carpi radialis',
                'Flexor carpi ulnaris',
                'Palmaris longus',
                'Flexor digitorum superficialis',
                'Flexor digitorum profundus',
            ],

            'Forearm Extensors' => [
                'Extensor carpi radialis longus',
                'Extensor carpi radialis brevis',
                'Extensor carpi ulnaris',
                'Extensor digitorum',
            ],

            'Abs' => [
                'Rectus abdominis',
            ],

            'Obliques' => [
                'Obliquus externus',
                'Obliquus internus',
            ],

            'Serratus Anterior' => [
                'Serratus Anterior',
            ],

            'Quadriceps' => [
                'Rectus femoris',
                'Vastus lateralis',
                'Vastus medialis',
                'Vastus intermedius',
            ],

            'Hamstrings' => [
                'Biceps femoris',
                'Semitendinosus',
                'Semimembranosus',
            ],

            'Calves' => [
                'Gastrocnemius',
                'Soleus',
            ],

            'Tibialis Anterior' => [
                'Tibialis anterior',
            ],

            'Glutes' => [
                'Gluteus maximus',
                'Gluteus medius',
                'Gluteus minimus',
            ],

            'Hip Flexors' => [
                'Iliopsoas',
            ],

            'Abductors' => [
                'Tensor Fasciae Latae',
            ],

            'Adductors' => [
                'Gracilis',
                'Pectineus',
                'Adductor Longus',
                'Adductor Brevis',
                'Adductor Magnus',
            ],
        ];

        foreach ($muscles as $group => $items) {

            $muscleGroup = MuscleGroup::where('name', $group)->firstOrFail();

            foreach ($items as $name) {

                Muscle::updateOrCreate(
                    [
                        'slug' => Str::slug($name),
                    ],
                    [
                        'muscle_group_id' => $muscleGroup->id,
                        'name' => $name,
                    ]
                );
            }
        }
    }
}
