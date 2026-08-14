<?php

namespace App\Models;

use App\Enum\ExerciseType;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'name', 'slug', 'type', 'description', 'image_path'])]
class Exercise extends Model
{
    use Sluggable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bodyParts(): BelongsToMany
    {
        return $this->belongsToMany(BodyPart::class, 'body_part_exercise');
    }

    public function muscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_muscle_groups')->withTimestamps();
    }

    public function muscles()
    {
        return $this->belongsToMany(Muscle::class)->withPivot('role');
    }

    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ExerciseType::class,
        ];
    }
}
