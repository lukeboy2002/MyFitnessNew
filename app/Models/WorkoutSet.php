<?php

namespace App\Models;

use App\Enum\ExerciseMetric;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['workout_session_id', 'workout_exercise_set_id', 'reps', 'weight', 'duration_seconds', 'distance_km', 'metric', 'metric_value', 'completed', 'notes'])]
class WorkoutSet extends Model
{
    public function workoutSession(): BelongsTo
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    public function workoutExercise(): BelongsTo
    {
        return $this->belongsTo(WorkoutExercise::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'distance_km' => 'decimal:2',
            'metric' => ExerciseMetric::class,
            'metric_value' => 'decimal:2',
            'completed' => 'boolean',
        ];
    }
}
