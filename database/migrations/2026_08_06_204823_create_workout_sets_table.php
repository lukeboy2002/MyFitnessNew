<?php

use App\Models\WorkoutExerciseSet;
use App\Models\WorkoutSession;
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
        Schema::create('workout_sets', function (Blueprint $table) {

            $table->id();
            $table->foreignIdFor(WorkoutSession::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(WorkoutExerciseSet::class)->nullable()->constrained()->nullOnDelete();

            /* Strength */
            $table->unsignedInteger('reps')->nullable();
            $table->decimal('weight', 8, 2)->nullable();

            /* Cardio */
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();

            /* Generic metric */
            $table->string('metric')->nullable();
            $table->decimal('metric_value', 8, 2)->nullable();
            $table->decimal('incline_percent', 5, 1)->nullable();

            /* Calories */
            $table->unsignedInteger('calories_total')->nullable();
            $table->unsignedInteger('calories_active')->nullable();

            /* Rowing */
            $table->unsignedInteger('stroke_rate')->nullable();
            $table->unsignedInteger('pace_seconds')->nullable();

            /* Stairmaster */
            $table->unsignedInteger('floors')->nullable();

            /* Bike */
            $table->unsignedInteger('rotations')->nullable();
            $table->decimal('avg_speed', 8, 2)->nullable();

            /* Machine */
            $table->decimal('mets', 8, 2)->nullable();
            $table->unsignedInteger('watts')->nullable();
            $table->unsignedInteger('avg_heart_rate')->nullable();
            $table->unsignedInteger('max_heart_rate')->nullable();

            /* General */
            $table->boolean('completed')->default(false);

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['workout_session_id', 'workout_exercise_set_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_sets');
    }
};
