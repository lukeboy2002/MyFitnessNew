<?php

namespace App\Livewire\Exercises;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ExerciseSelector extends Component
{
    public string $search = '';

    public ?int $filterMuscleGroup = null;

    public function selectExercise(int $exerciseId): void
    {
        $exercise = Exercise::visibleTo()
            ->findOrFail($exerciseId);

        $this->dispatch(
            'exercise-selected',
            exerciseId: $exercise->id,
        );
    }

    public function render(): View
    {
        $muscleGroups = MuscleGroup::query()
            ->orderBy('name')
            ->get();

        $query = Exercise::visibleTo()
            ->with([
                'bodyParts',
                'muscleGroups',
            ])
            ->orderBy('name');

        if (filled($this->search)) {
            $query->where(
                'name',
                'like',
                '%'.trim($this->search).'%'
            );
        }

        if ($this->filterMuscleGroup) {
            $query->whereHas(
                'muscleGroups',
                fn ($query) => $query->where(
                    'muscle_groups.id',
                    $this->filterMuscleGroup
                )
            );
        }

        $exercises = $query
            ->limit(50)
            ->get();

        return view(
            'livewire.exercises.exercise-selector',
            [
                'exercises' => $exercises,
                'muscleGroups' => $muscleGroups,
            ],
        );
    }
}
