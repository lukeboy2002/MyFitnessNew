<?php

namespace App\Livewire\Muscle;

use App\Models\BodyPart;
use App\Models\Muscle;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Livewire\Attributes\Rule;
use Livewire\Component;

class MuscleForm extends Component
{
    public ?Muscle $muscle = null;

    #[Rule('required|min:3|max:255')]
    public ?string $name = '';

    #[Rule('required|min:3|max:255')]
    public ?string $slug = '';

    #[Rule('required|exists:muscle_groups,id')]
    public ?int $muscle_group_id = null;

    public function updatedName(): void
    {
        $this->slug = SlugService::createSlug(Muscle::class, 'slug', $this->name ?? '');
    }

    public function mount(?Muscle $muscle = null): void
    {
        $this->muscle = $muscle;

        if ($muscle?->exists) {
            $this->name = $this->muscle->name;
            $this->slug = $this->muscle->slug;
            $this->muscle_group_id = $muscle->muscle_group_id;
        }
    }

    public function save()
    {
        $this->validate();

        $muscle = $this->muscle ?? new Muscle;

        $muscle->fill([
            'name' => $this->name,
            'slug' => $this->slug,
            'muscle_group_id' => $this->muscle_group_id,
        ]);
        $muscle->save();

        $this->dispatch('muscle-saved');

        flash()->success(__('The muscle has been saved'));

        return redirect()->route('muscles.index');
    }

    public function render()
    {
        $bodyParts = BodyPart::with([
            'muscleGroups' => fn ($query) => $query->orderBy('name'),
        ])
            ->orderBy('name')
            ->get();

        return view('livewire.muscle.muscle-form', [
            'bodyParts' => $bodyParts,
        ]);

        //        $musclegroups = MuscleGroup::orderBy('name')->get();
        //
        //        return view('livewire.muscle.muscle-form', [
        //            'musclegroups' => $musclegroups,
        //        ]);
    }
}
