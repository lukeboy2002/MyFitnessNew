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
                'Scalenen',
                'Splenius capitis',
                'Splenius cervicis',
            ],

            'Traps' => [
                'Trapezius',
            ],

            'Upper Back' => [
                'Levator scapulae',
                'Serratus posterior',
            ],

            // Rhomboideus stond eerst onder 'Upper Back'
            'Rhomboids' => [
                'Rhomboideus',
            ],

            'Lats' => [
                'Latissimus dorsi',
                'Teres major',
            ],

            'Lower Back' => [
                'Erector spinae',
                'Multifidus',
                'Quadratus lumborum',
            ],

            'Biceps' => [
                'Biceps brachii',
                'Brachialis',
                'Coracobrachialis',
            ],

            'Triceps' => [
                'Triceps brachii',
                'Anconeus',
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

            'Chest' => [
                'Pectoralis major',
                'Pectoralis minor',
            ],

            'Forearm Flexors' => [
                'Brachioradialis',
                'Pronator teres',
                'Flexor carpi radialis',
                'Flexor carpi ulnaris',
                'Palmaris longus',
                'Flexor digitorum superficialis',
                'Flexor digitorum profundus',
            ],

            'Forearm Extensors' => [
                'Supinator',
                'Extensor carpi radialis longus',
                'Extensor carpi radialis brevis',
                'Extensor carpi ulnaris',
                'Extensor digitorum',
            ],

            'Abs' => [
                'Rectus abdominis',
                'Transversus abdominis',
            ],

            'Obliques' => [
                'Obliquus externus',
                'Obliquus internus',
            ],

            'Serratus Anterior' => [
                'Serratus anterior',
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
                'Popliteus',
                'Tibialis posterior',
            ],

            'Tibialis Anterior' => [
                'Tibialis anterior',
            ],

            'Peroneals' => [
                'Peroneus longus',
                'Peroneus brevis',
            ],

            'Glutes' => [
                'Gluteus maximus',
                'Gluteus medius',
                'Gluteus minimus',
                'Piriformis',
            ],

            'Hip Flexors' => [
                'Iliopsoas',
                'Sartorius',
            ],

            'Abductors' => [
                'Tensor fasciae latae',
            ],

            'Adductors' => [
                'Gracilis',
                'Pectineus',
                'Adductor longus',
                'Adductor brevis',
                'Adductor magnus',
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
