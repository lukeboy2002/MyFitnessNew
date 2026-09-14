<?php

namespace App\Livewire\Muscle;

use App\Models\BodyPart;
use App\Models\Muscle;
use App\Models\MuscleGroup;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MuscleIndex extends Component
{
    use WithPagination;

    #[Url(as: 'musclegroup')]
    public string $muscleGroupFilter = 'all';

    #[Url(as: 'bodypart')]
    public string $bodyPartFilter = 'all';

    public $muscle;

    #[Url(as: 'q')]
    public string $search = '';

    protected $listeners = [];

    #[Computed]
    public function bodyPartFilterName()
    {
        if ($this->bodyPartFilter === 'all') {
            return null;
        }

        return BodyPart::find($this->bodyPartFilter)?->name;
    }

    #[Computed]
    public function muscleGroupFilterName()
    {
        if ($this->muscleGroupFilter === 'all') {
            return null;
        }

        return MuscleGroup::find($this->muscleGroupFilter)?->name;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->bodyPartFilter = 'all';
        $this->muscleGroupFilter = 'all';
        $this->search = '';
        $this->resetPage();
    }

    public function deleteItem(Muscle $muscle)
    {
        $this->muscle = $muscle;
        $this->dispatch('open-modal', 'delete-muscle');
    }

    public function confirmDelete()
    {
        if ($this->muscle) {

            $this->muscle->delete();

            flash()->success(__('The muscle has been deleted'));

            $this->dispatch('muscle-deleted');
            $this->dispatch('close-modal', 'delete-muscle');
            $this->muscle = null;
        }
    }

    #[Layout('layouts.app', ['pageTitle' => 'Muscles'])]
    public function render()
    {
        $musclegroups = MuscleGroup::orderBy('name')->get();
        $bodyparts = BodyPart::orderBy('name')->get();

        $muscles = Muscle::with('muscleGroup', 'muscleGroup.bodyPart')
            ->when(filled($this->search), function ($query) {
                $query->where('name', 'like', '%'.trim($this->search).'%');
            })
            ->when($this->muscleGroupFilter !== 'all', function ($query) {
                $query->where('muscle_group_id', $this->muscleGroupFilter);
            })
            ->when($this->bodyPartFilter !== 'all', function ($query) {
                $query->whereHas('muscleGroup', function ($q) {
                    $q->where('body_part_id', $this->bodyPartFilter);
                });
            })
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.muscle.muscle-index', [
            'muscles' => $muscles,
            'musclegroups' => $musclegroups,
            'bodyparts' => $bodyparts,
        ]);
    }
}
