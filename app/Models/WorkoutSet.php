<?php

namespace App\Models;

use App\Enum\ExerciseMetric;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'workout_session_id',
    'workout_exercise_set_id',

    /* Strength */
    'reps',
    'weight',

    /* Cardio */
    'duration_seconds',
    'distance_km',
    'metric',
    'metric_value',
    'incline_percent',

    /* Calories */
    'calories_total',
    'calories_active',

    /* Rowing */
    'stroke_rate',
    'pace_seconds',

    /* Stairmaster */
    'floors',

    /* Bike */
    'rotations',
    'avg_speed',

    /* Machine */
    'mets',
    'watts',
    'avg_heart_rate',
    'max_heart_rate',

    /* General */
    'completed',
    'notes',
])]
class WorkoutSet extends Model
{
    public function workoutSession(): BelongsTo
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    public function workoutExerciseSet(): BelongsTo
    {
        return $this->belongsTo(WorkoutExerciseSet::class);
    }

    public function splits(): HasMany
    {
        return $this->hasMany(WorkoutSetSplit::class);
    }

    protected function casts(): array
    {
        return [

            /* Strength */
            'reps' => 'integer',
            'weight' => 'decimal:2',

            /* Cardio */
            'duration_seconds' => 'integer',
            'distance_km' => 'decimal:2',
            'metric' => ExerciseMetric::class,
            'metric_value' => 'decimal:2',
            'incline_percent' => 'decimal:1',

            /* Calories */
            'calories_total' => 'integer',
            'calories_active' => 'integer',

            /* Rowing */
            'stroke_rate' => 'integer',
            'pace_seconds' => 'integer',

            /* Stairmaster */
            'floors' => 'integer',

            /* Bike */
            'rotations' => 'integer',
            'avg_speed' => 'decimal:2',

            /* Machine */
            'mets' => 'decimal:2',
            'watts' => 'integer',
            'avg_heart_rate' => 'integer',
            'max_heart_rate' => 'integer',

            /* General */
            'completed' => 'boolean',
        ];
    }
}
