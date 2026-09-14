<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'workout_id',
    'started_at',
    'completed_at',
    'completed',
    'notes',
])]
class WorkoutSession extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    /** Actual completed sets. */
    public function workoutSets(): HasMany
    {
        return $this->hasMany(WorkoutSet::class);
    }

    /** Workout duration in seconds. */
    public function getDurationInSecondsAttribute(): int
    {
        if (! $this->started_at || ! $this->completed_at) {
            return 0;
        }

        return $this->started_at->diffInSeconds($this->completed_at);
    }

    /**
     * Formatted workout duration.
     *
     * Examples:
     * 45 min
     * 1h 15m
     */
    public function getFormattedDurationAttribute(): string
    {
        $seconds = $this->duration_in_seconds;

        $hours = floor($seconds / 3600);

        $minutes = floor(
            ($seconds % 3600) / 60
        );

        if ($hours > 0) {
            return $hours.'h '.$minutes.'m';
        }

        return $minutes.' min';
    }

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'completed' => 'boolean',
        ];
    }
}
