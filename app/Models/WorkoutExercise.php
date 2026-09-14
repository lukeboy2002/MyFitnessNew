<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'workout_id',
    'exercise_id',
    'order',
    'notes',
])]
class WorkoutExercise extends Model
{
    use HasFactory;

    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Set templates for this exercise in the workout.
     */
    public function workoutExerciseSets(): HasMany
    {
        return $this->hasMany(WorkoutExerciseSet::class)->orderBy('set_number');
    }

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }
}
