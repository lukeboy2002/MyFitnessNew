<?php

namespace App\Models;

use App\Enum\ExerciseMetric;
use App\Enum\WorkoutSetType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'workout_exercise_id',
    'set_number',
    'type',
    /* Strength */
    'target_reps',
    'target_weight',
    'rest_seconds',
    /* Cardio */
    'target_duration_seconds',
    'target_duration_minutes',
    'target_distance_km',
    'target_metric',
    'target_metric_value',
    'target_incline_percent',
])]

class WorkoutExerciseSet extends Model
{
    use HasFactory;

    public function workoutExercise(): BelongsTo
    {
        return $this->belongsTo(WorkoutExercise::class);
    }

    public function workoutSets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class);
    }

    protected function casts(): array
    {
        return [
            'type' => WorkoutSetType::class,
            'target_metric' => ExerciseMetric::class,

            /* Strength */
            'target_reps' => 'integer',
            'target_weight' => 'decimal:2',

            /* Cardio */
            'target_duration_seconds' => 'integer',
            'target_distance_km' => 'decimal:2',
            'target_metric_value' => 'decimal:2',
            'target_incline_percent' => 'decimal:1',

            /* General */
            'rest_seconds' => 'integer',
        ];
    }
}
