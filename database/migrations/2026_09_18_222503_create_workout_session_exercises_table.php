<?php

use App\Models\WorkoutExercise;
use App\Models\WorkoutSession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_session_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(WorkoutSession::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(WorkoutExercise::class)->constrained()->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(
                ['workout_session_id', 'order'],
                'session_exercise_order_index'
            );

            $table->unique(
                ['workout_session_id', 'workout_exercise_id'],
                'session_exercise_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_session_exercises');
    }
};
