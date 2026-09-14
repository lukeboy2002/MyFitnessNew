<?php

namespace App\Livewire\Musclegroups;

use App\Models\BodyPart;
use App\Models\MuscleGroup;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class MusclegroupIndex extends Component
{
    #[Url(as: 'bodypart')]
    public string $bodyPartFilter = 'all';

    public $musclegroup;

    protected $listeners = [];

    public function deleteItem(MuscleGroup $musclegroup)
    {
        $this->musclegroup = $musclegroup;
        $this->dispatch('open-modal', 'delete-musclegroup');
    }

    public function confirmDelete()
    {

        if ($this->musclegroup) {

            $this->musclegroup->delete();

            if ($this->musclegroup->image_path) {
                Storage::disk('public')->delete($this->musclegroup->image_path);
            }

            flash()->success(__('The musclegroup has been deleted'));

            $this->dispatch('musclegroup-deleted');
            $this->dispatch('close-modal', 'delete-musclegroup');
            $this->musclegroup = null;
        }
    }

    #[Layout('layouts.app', ['pageTitle' => 'Musclegroups'])]
    public function render()
    {
        $bodyparts = BodyPart::orderBy('name')->get();

        $musclegroups = MuscleGroup::with('bodyPart')
            ->when($this->bodyPartFilter !== 'all', function ($query) {
                $query->where('body_part_id', $this->bodyPartFilter);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.musclegroups.musclegroup-index', [
            'musclegroups' => $musclegroups,
            'bodyparts' => $bodyparts,
        ]);
    }
}
