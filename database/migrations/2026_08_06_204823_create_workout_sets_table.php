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

            // Werkelijke resultaten
            // Kracht
            $table->unsignedInteger('reps')->nullable();
            $table->decimal('weight', 8, 2)->nullable();

            // Cardio
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();

            // Extra waarde: incline, resistance, watts...
            $table->string('metric')->nullable();
            $table->decimal('metric_value', 8, 2)->nullable();

            $table->boolean('completed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['workout_session_id']);
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
