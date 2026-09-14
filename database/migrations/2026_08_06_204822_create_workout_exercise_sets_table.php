<?php

use App\Models\WorkoutExercise;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            'workout_exercise_sets',
            function (Blueprint $table) {

                $table->id();
                $table->foreignIdFor(WorkoutExercise::class)->constrained()->cascadeOnDelete();

                /* Set */
                $table->unsignedInteger('set_number');
                $table->string('type')->default('working');

                /* Strength */
                $table->unsignedInteger('target_reps')->nullable();
                $table->decimal('target_weight', 8, 2)->nullable();
                $table->unsignedInteger('rest_seconds')->nullable();

                /* Cardio */
                $table->unsignedInteger('target_duration_seconds')->nullable();
                $table->decimal('target_distance_km', 8, 2)->nullable();

                /* Cardio metric */
                $table->string('target_metric')->nullable();
                $table->decimal('target_metric_value', 8, 2)->nullable();
                $table->decimal('target_incline_percent', 4, 1)->nullable();

                $table->timestamps();

                $table->unique(['workout_exercise_id', 'set_number']);
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_exercise_sets');
    }
};
