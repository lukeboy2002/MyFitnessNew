<?php

namespace App\Livewire\Exercises;

use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\WorkoutSet;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
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

    public $bodyPartId = '';

    public $muscleGroupId = '';

    protected $listeners = [];

    #[Computed]
    public function bodyPartName(): ?string
    {
        if (! $this->bodyPartId) {
            return null;
        }

        return BodyPart::find($this->bodyPartId)?->name;
    }

    #[Computed]
    public function muscleGroupName(): ?string
    {
        if (! $this->muscleGroupId) {
            return null;
        }

        return MuscleGroup::find($this->muscleGroupId)?->name;
    }

    public function updatingSearch(): void
    {
        $this->perPage = 10;
    }

    public function updatingType(): void
    {
        $this->perPage = 10;
    }

    public function updatingBodyPartId(): void
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
            ->with(['muscleGroups', 'bodyParts'])
            ->addSelect([
                'performed_count' => WorkoutSet::query()
                    ->selectRaw('COUNT(DISTINCT workout_sets.workout_session_id)')
                    ->where('workout_sets.completed', true)
                    ->whereHas('workoutSession', function ($query) use ($userId) {
                        $query
                            ->where('user_id', $userId)
                            ->where('completed', true);
                    })
                    ->whereHas('workoutExerciseSet.workoutExercise', function ($query) {
                        $query->whereColumn(
                            'workout_exercises.exercise_id',
                            'exercises.id'
                        );
                    }),
            ])
            ->when(filled($this->search), function ($query) {
                $query->where(
                    'name',
                    'like',
                    '%'.trim($this->search).'%'
                );
            });

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->bodyPartId) {
            $query->whereHas('bodyParts', function ($q) {
                $q->where('id', $this->bodyPartId);
            });
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
            'bodyParts' => BodyPart::orderBy('name')->get(),
            'muscleGroups' => MuscleGroup::orderBy('name')->get(),
            'hasMore' => $this->perPage < $total,
        ]);
    }
}
