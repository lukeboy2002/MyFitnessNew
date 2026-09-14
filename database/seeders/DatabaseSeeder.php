<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@myfitness.test',
            'password' => bcrypt('adminadmin'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Antoine Hendriks',
            'username' => 'Antoine',
            'email' => 'antoine@myfitness.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Alice Hendriks',
            'username' => 'Alice',
            'email' => 'alice@myfitness.test',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        User::factory()->create([
            'name' => 'Amy Hendriks',
            'username' => 'Amy',
            'email' => 'amy@myfitness.test',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        $this->call([
            CarouselSeeder::class,
            BodyPartSeeder::class,
            MuscleGroupSeeder::class,
            MuscleSeeder::class,
            ExerciseSeeder::class,
            ExerciseBodyPartSeeder::class,
            ExerciseMuscleGroupSeeder::class,
            ExerciseMuscleSeeder::class,
            ExerciseShowSeeder::class,
        ]);
    }
}
