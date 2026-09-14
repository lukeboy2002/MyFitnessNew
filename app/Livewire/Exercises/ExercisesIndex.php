<?php

namespace App\Livewire\Exercises;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class ExercisesIndex extends Component
{
    public $exercise;

    #[Url(as: 'q')]
    public string $search = '';

    public int $perPage = 10;

    public $type = '';

    public $muscleGroupId = '';

    protected $listeners = [];

    public function updatingSearch(): void
    {
        $this->perPage = 10;
    }

    public function updatingType(): void
    {
        $this->perPage = 10;
    }

    public function updatingMuscleGroupId(): void
    {
        $this->perPage = 10;
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function deleteItem(Exercise $exercise): void
    {
        $this->authorize('delete', $exercise);

        $this->exercise = $exercise;
        $this->dispatch('open-modal', 'delete-exercice');
    }

    public function confirmDelete(): void
    {
        if ($this->exercise) {
            $this->authorize('delete', $this->exercise);

            $this->exercise->delete();

            if ($this->exercise->image_path) {
                Storage::disk('public')->delete($this->exercise->image_path);
            }

            flash()->success(__('The exercise has been deleted'));

            $this->dispatch('exercise-deleted');
            $this->dispatch('close-modal', 'delete-exercice');
            $this->exercise = null;
        }
    }

    #[Layout('layouts.app', ['pageTitle' => 'Exercises'])]
    public function render()
    {
        $userId = auth()->id();

        $query = Exercise::visibleTo()
            ->orderByRaw('CASE WHEN user_id = ? THEN 0 ELSE 1 END', [$userId])
            ->orderBy('name')
            ->with('muscleGroups')
            ->when(filled($this->search), function ($query) {
                $query->where('name', 'like', '%'.trim($this->search).'%');
            });

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->muscleGroupId) {
            $query->whereHas('muscleGroups', function ($q) {
                $q->where('id', $this->muscleGroupId);
            });
        }

        $total = $query->count();

        $exercises = $query->take($this->perPage)->get();

        return view('livewire.exercises.exercises-index', [
            'exercises' => $exercises,
            'muscleGroups' => MuscleGroup::orderBy('name')->get(),
            'hasMore' => $this->perPage < $total,
        ]);
    }
}
