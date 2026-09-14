<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'name',
    'slug',
    'is_archived',
])]

class Workout extends Model
{
    use HasFactory;
    use Sluggable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workoutExercises(): HasMany
    {
        return $this
            ->hasMany(WorkoutExercise::class)->orderBy('order');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(WorkoutSession::class);
    }

    public function saveItem(array $data): void
    {
        $this->fill($data);

        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function archive(): void
    {
        $this->update(['is_archived' => true]);
    }

    public function unarchive(): void
    {
        $this->update(['is_archived' => false]);
    }

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'name'],
        ];
    }

    protected function casts(): array
    {
        return [
            'is_archived' => 'boolean',
        ];
    }
}
