<?php

namespace Database\Seeders;

use App\Models\Muscle;
use App\Models\MuscleGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MuscleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $muscles = [

            'Neck' => [
                'Sternocleidomastoideus',
            ],

            'Traps' => [
                'Trapezius',
                'Levator scapulae',
            ],

            'Biceps' => [
                'Biceps brachii',
                'Brachialis',
                'Brachioradialis',
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

            'Upper Back' => [
                'Rhomboideus',
                'Teres major',
            ],

            'Lats' => [
                'Latissimus dorsi',
            ],

            'Lower Back' => [
                'Erector spinae',
                'Multifidus',
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

            $muscleGroup = MuscleGroup::where('name', $group)->first();

            foreach ($items as $name) {

                Muscle::create([
                    'muscle_group_id' => $muscleGroup->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                ]);

            }
        }

    }
}
