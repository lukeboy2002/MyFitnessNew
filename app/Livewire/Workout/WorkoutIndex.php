<?php

namespace App\Livewire\Workout;

use App\Models\Workout;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class WorkoutIndex extends Component
{
    use WithPagination;

    public ?Workout $workoutToArchive = null;

    public ?Workout $detailWorkout = null;

    public bool $isUnarchiving = false;

    public function showDetails(Workout $workout): void
    {
        abort_if($workout->user_id !== Auth::id(), 403);

        $this->loadWorkoutRelations($workout);

        $this->detailWorkout = $workout;
        $this->dispatch('open-modal', 'detail-workout');
    }

    protected function loadWorkoutRelations(Workout $workout): void
    {
        $workout->loadMissing([
            'workoutExercises' => fn ($query) => $query->orderBy('order'),
            'workoutExercises.exercise.bodyParts',
            'workoutExercises.exercise.muscleGroups',
            'workoutExercises.workoutExerciseSets' => fn ($query) => $query->orderBy('set_number'),
        ]);
    }

    public function confirmToggleArchive(Workout $workout, bool $unarchive = false): void
    {
        $this->workoutToArchive = $workout;
        $this->isUnarchiving = $unarchive;
        $this->dispatch('open-modal', 'confirm-workout-archive');
    }

    public function toggleArchive(): void
    {
        if ($this->workoutToArchive) {
            if ($this->isUnarchiving) {
                $this->workoutToArchive->unarchive();
                flash()->success(__('The workout has been restored'));
            } else {
                $this->workoutToArchive->archive();
                flash()->success(__('The workout has been archived'));
            }

            $this->dispatch('close-modal', 'confirm-workout-archive');
            $this->workoutToArchive = null;
            $this->isUnarchiving = false;
        }
    }

    #[Layout('layouts.app', ['pageTitle' => 'Workouts'])]
    public function render(): View
    {
        if ($this->detailWorkout) {
            $this->loadWorkoutRelations($this->detailWorkout);
        }

        $active = Workout::withCount('workoutExercises')
            ->where('user_id', Auth::id())
            ->active()
            ->orderBy('name')
            ->get();

        $archived = Workout::withCount('workoutExercises')
            ->where('user_id', Auth::id())
            ->archived()
            ->orderBy('name')
            ->get();

        return view('livewire.workout.workout-index', [
            'active' => $active,
            'archived' => $archived,
        ]);
    }
}
