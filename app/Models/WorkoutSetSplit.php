<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'workout_set_id',
    'split_number',
    'distance_km',
    'duration_seconds',
    'pace_seconds',
])]
class WorkoutSetSplit extends Model
{
    //    use HasFactory;

    public function workoutSet(): BelongsTo
    {
        return $this->belongsTo(
            WorkoutSet::class
        );
    }

    protected function casts(): array
    {
        return [
            'split_number' => 'integer',
            'distance_km' => 'decimal:2',
            'duration_seconds' => 'integer',
            'pace_seconds' => 'integer',
        ];
    }
}
