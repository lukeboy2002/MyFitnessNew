<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'workout_session_id',
    'workout_exercise_id',
    'order',
    'notes',
    'removed',
])]
class WorkoutSessionExercise extends Model
{
    public function workoutSession(): BelongsTo
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    public function workoutExercise(): BelongsTo
    {
        return $this->belongsTo(WorkoutExercise::class);
    }

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'removed' => 'boolean',
        ];
    }
}
