<?php

namespace App\Models;

use App\Enum\ExerciseType;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

#[Fillable([
    'user_id',
    'name',
    'slug',
    'type',
    'description',
    'howto',
    'image_path',
])]

class Exercise extends Model
{
    use HasFactory, Sluggable;

    protected $casts = [
        'type' => ExerciseType::class,
    ];

    public function bodyParts(): BelongsToMany
    {
        return $this->belongsToMany(BodyPart::class, 'body_part_exercise');
    }

    public function muscleGroups()
    {
        return $this->belongsToMany(
            MuscleGroup::class,
            'exercise_muscle_group'
        );
    }

    public function muscles()
    {
        return $this->belongsToMany(
            Muscle::class,
            'exercise_muscle'
        )->withPivot('role');
    }

    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    /**
     * Scope a query to only include exercises visible to the given user.
     */
    public function scopeVisibleTo(Builder $query, ?User $user = null): Builder
    {
        $user = $user ?? auth()->user();

        if ($user?->is_admin) {
            return $query->whereNull('exercises.user_id');
        }

        if ($user) {
            return $query->where(function (Builder $q) use ($user) {
                $q->whereNull('exercises.user_id')
                    ->orWhere('exercises.user_id', $user->id);
            });
        }

        return $query->whereNull('exercises.user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function saveItem(array $data, ?UploadedFile $image = null): void
    {
        $this->fill($data);

        if ($image) {
            $this->saveImage($image);
        }

        $this->save();
    }

    public function saveImage(UploadedFile $image): void
    {
        $filename = $image->hashName();
        $directory = 'exercises';

        Storage::disk('public')->makeDirectory($directory);

        $targetPath = storage_path("app/public/{$directory}/{$filename}");

        Image::load($image->getRealPath())
            ->fit(Fit::Max, 1200, 1200)
            ->optimize()
            ->save($targetPath);

        if ($this->image_path) {
            Storage::disk('public')->delete($this->image_path);
        }

        $this->image_path = "{$directory}/{$filename}";
    }

    public function isCardio(): bool
    {
        return $this->type === ExerciseType::Cardio || $this->type === ExerciseType::Cardio->value;
    }

    public function isStrength(): bool
    {
        return $this->type === ExerciseType::Strength || $this->type === ExerciseType::Strength->value;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    protected function casts(): array
    {
        return [
            'type' => ExerciseType::class,
        ];
    }
}
