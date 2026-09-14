<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

#[Fillable([
    'author',
    'link',
    'image_path',
    'is_active',
])]
class Carousel extends Model
{
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
        $directory = 'carousel';

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

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    protected function link(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => blank($value)
                ? null
                : (
                    str_starts_with($value, 'http://')
                    || str_starts_with($value, 'https://')
                        ? $value
                        : 'https://'.$value
                ),
        );
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
