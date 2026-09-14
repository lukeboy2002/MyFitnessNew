<?php

use App\Models\WorkoutSet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'workout_set_splits',
            function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(WorkoutSet::class)->constrained()->cascadeOnDelete();
                $table->unsignedInteger('split_number');
                $table->decimal('distance_km', 8, 2);
                $table->unsignedInteger('duration_seconds');
                $table->unsignedInteger('pace_seconds')->nullable();
                $table->timestamps();

                $table->unique(['workout_set_id', 'split_number']);
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_set_splits');
    }
};
