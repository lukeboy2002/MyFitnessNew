<?php

namespace App\Models;

use App\Enum\ExerciseMetric;
use App\Enum\WorkoutSetType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['workout_exercise_id', 'set_number', 'type', 'target_reps', 'target_weight', 'target_duration_seconds', 'target_distance_km', 'target_metric', 'target_metric_value', 'rest_seconds'])]
class WorkoutExerciseSet extends Model
{
    public function workoutExercise(): BelongsTo
    {
        return $this->belongsTo(WorkoutExercise::class);
    }

    public function workoutSets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => WorkoutSetType::class,
            'target_metric' => ExerciseMetric::class,
            'target_weight' => 'decimal:2',
            'target_distance_km' => 'decimal:2',
            'target_metric_value' => 'decimal:2',
        ];
    }
}
