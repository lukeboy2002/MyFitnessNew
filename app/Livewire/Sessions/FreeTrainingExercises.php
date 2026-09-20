<?php

namespace App\Livewire\Sessions;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\WorkoutSession;
use App\Services\WorkoutExerciseService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FreeTrainingExercises extends Component
{
    public WorkoutSession $session;

    public string $searchExercise = '';

    public ?int $filterMuscleGroup = null;

    public function mount(WorkoutSession $session): void
    {
        abort_unless(
            $session->user_id === auth()->id(),
            403
        );

        $this->session = $session;
    }

    public function addExercise(
        int $exerciseId,
        WorkoutExerciseService $workoutExerciseService
    ): void {
        abort_unless(
            $this->session->user_id === auth()->id(),
            403
        );

        abort_if(
            $this->session->completed,
            422
        );

        $exercise = Exercise::visibleTo()
            ->findOrFail($exerciseId);

        $alreadyExists = $this->session
            ->workoutSessionExercises()
            ->whereHas(
                'workoutExercise',
                fn ($query) => $query->where(
                    'exercise_id',
                    $exercise->id
                )
            )
            ->exists();

        if ($alreadyExists) {
            flash()->warning(
                __('This exercise is already added to the workout')
            );

            return;
        }

        $nextOrder = (
            $this->session
                ->workoutSessionExercises()
                ->max('order') ?? 0
        ) + 1;

        DB::transaction(function () use (
            $workoutExerciseService,
            $exercise
        ): void {
            $this->session
                ->workoutSessionExercises()
                ->increment('order');

            $workoutExercise = $workoutExerciseService->create(
                exercise: $exercise,
                workoutId: null,
                order: 0,
            );

            $this->session
                ->workoutSessionExercises()
                ->create([
                    'workout_exercise_id' => $workoutExercise->id,
                    'order' => 0,
                ]);
        });

        flash()->success(
            __('Exercise added to workout')
        );

        $this->dispatch(
            'close-modal',
            'add-exercise-modal'
        );
    }

    public function render(): View
    {
        $allMuscleGroups = MuscleGroup::query()
            ->orderBy('name')
            ->get();

        $query = Exercise::visibleTo()
            ->with([
                'bodyParts',
                'muscleGroups',
            ])
            ->orderBy('name');

        if (filled($this->searchExercise)) {
            $query->where(
                'name',
                'like',
                '%'.trim($this->searchExercise).'%'
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

        $availableExercises = $query
            ->limit(50)
            ->get();

        $alreadyAddedExerciseIds = $this->session
            ->workoutSessionExercises()
            ->with('workoutExercise')
            ->get()
            ->pluck('workoutExercise.exercise_id')
            ->all();

        $sessionExercises = $this->session
            ->workoutSessionExercises()
            ->with([
                'workoutExercise.exercise',
                'workoutExercise.workoutExerciseSets',
            ])
            ->orderBy('order')
            ->get();

        return view(
            'livewire.sessions.free-training-exercises',
            [
                'all_muscle_groups' => $allMuscleGroups,
                'available_exercises' => $availableExercises,
                'already_added_exercise_ids' => $alreadyAddedExerciseIds,
                'sessionExercises' => $sessionExercises,
            ],
        );
    }
}
