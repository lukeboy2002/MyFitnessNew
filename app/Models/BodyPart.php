<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

#[Fillable(['name', 'slug', 'image_path'])]
class BodyPart extends Model
{
    use HasFactory, Sluggable;

    public function musclesGroups(): HasMany
    {
        return $this->hasMany(MuscleGroup::class);
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(
            Exercise::class,
            'body_part_exercise'
        );
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
        $directory = 'bodyparts';

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

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}
