<?php

namespace App\Livewire\Musclegroups;

use App\Models\BodyPart;
use App\Models\MuscleGroup;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class MusclegroupForm extends Component
{
    use WithFileUploads;

    public ?MuscleGroup $musclegroup = null;

    #[Rule('nullable|image|max:10240|mimes:jpg,jpeg,png,webp,heic,heif')]
    public $image_path;

    #[Rule('required|min:3|max:255')]
    public ?string $name = '';

    #[Rule('required|min:3|max:255')]
    public ?string $slug = '';

    #[Rule('required|exists:body_parts,id')]
    public ?int $body_part_id = null;

    public function updatedName(): void
    {
        $this->slug = SlugService::createSlug(MuscleGroup::class, 'slug', $this->name ?? '');
    }

    public function mount(?MuscleGroup $musclegroup = null): void
    {
        $this->musclegroup = $musclegroup;

        if ($musclegroup?->exists) {
            $this->name = $this->musclegroup->name;
            $this->slug = $this->musclegroup->slug;
            $this->body_part_id = $musclegroup->body_part_id;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'body_part_id' => $this->body_part_id,
        ];

        $musclegroup = $this->musclegroup ?? new MuscleGroup;

        $musclegroup->saveItem($data, $this->image_path);

        $this->dispatch('musclegroup-saved');

        flash()->success(__('The musclegroup has been saved'));

        return redirect()->route('musclegroups.index');
    }

    #[Layout('layouts.app', ['pageTitle' => 'Musclegroups'])]
    public function render()
    {
        $bodyparts = BodyPart::orderBy('name')->get();

        return view('livewire.musclegroups.musclegroup-form', [
            'bodyparts' => $bodyparts,
        ]);
    }
}
