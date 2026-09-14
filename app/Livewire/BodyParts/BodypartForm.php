<?php

namespace App\Livewire\BodyParts;

use App\Models\BodyPart;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class BodypartForm extends Component
{
    use WithFileUploads;

    public ?BodyPart $bodypart = null;

    #[Rule('nullable|image|max:10240|mimes:jpg,jpeg,png,webp,heic,heif')]
    public $image_path;

    #[Rule('required|min:3|max:255')]
    public ?string $name = '';

    public ?string $slug = '';

    public function updatedName(): void
    {
        $this->slug = SlugService::createSlug(BodyPart::class, 'slug', $this->name ?? '');
    }

    public function mount(?BodyPart $bodypart = null): void
    {
        $this->bodypart = $bodypart;

        if ($bodypart?->exists) {
            $this->name = $bodypart->name;
            $this->slug = $bodypart->slug;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
        ];

        $bodypart = $this->bodypart ?? new BodyPart;

        $bodypart->saveItem($data, $this->image_path);

        $this->dispatch('bodypart-saved');

        flash()->success(__('The bodypart has been saved'));

        return redirect()->route('bodyparts.index');
    }

    #[Layout('layouts.app', ['pageTitle' => 'Bodypart'])]
    public function render()
    {
        return view('livewire.body-parts.bodypart-form');
    }
}
