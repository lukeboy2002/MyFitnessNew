<?php

namespace App\Livewire\Sessions;

use App\Enum\ExerciseType;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\WorkoutSession;
use App\Services\WorkoutExerciseService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SessionExercises extends Component
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
            ->where('removed', false)
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

        DB::transaction(function () use (
            $workoutExerciseService,
            $exercise
        ): void {
            $nextOrder = (
                $this->session
                    ->workoutSessionExercises()
                    ->where('removed', false)
                    ->max('order') ?? 0
            ) + 1;

            $removedSessionExercise = $this->session
                ->workoutSessionExercises()
                ->where('removed', true)
                ->whereHas(
                    'workoutExercise',
                    fn ($query) => $query->where(
                        'exercise_id',
                        $exercise->id
                    )
                )
                ->first();

            if ($removedSessionExercise) {
                $removedSessionExercise->update([
                    'removed' => false,
                    'order' => $nextOrder,
                ]);
            } else {
                $workoutExercise = $workoutExerciseService->create(
                    exercise: $exercise,
                    workoutId: null,
                    order: $nextOrder,
                );

                $this->session
                    ->workoutSessionExercises()
                    ->create([
                        'workout_exercise_id' => $workoutExercise->id,
                        'order' => $nextOrder,
                        'removed' => false,
                    ]);
            }

            $this->reorderExercises();
        });

        flash()->success(
            __('Exercise added to workout')
        );

        $this->dispatch(
            'close-modal',
            'add-exercise-modal'
        );
    }

    public function removeExercise(int $sessionExerciseId): void
    {
        abort_unless(
            $this->session->user_id === auth()->id(),
            403
        );

        if ($this->session->completed) {
            return;
        }

        $sessionExercise = $this->session
            ->workoutSessionExercises()
            ->whereKey($sessionExerciseId)
            ->where('removed', false)
            ->first();

        if (! $sessionExercise) {
            return;
        }

        $sessionExercise->update([
            'removed' => true,
        ]);

        $this->reorderExercises();

        flash()->success(__('Exercise removed from workout'));
    }

    public function moveExerciseUp(int $sessionExerciseId): void
    {
        abort_unless(
            $this->session->user_id === auth()->id(),
            403
        );

        if ($this->session->completed) {
            return;
        }

        $this->reorderExercises();

        $current = $this->session
            ->workoutSessionExercises()
            ->where('removed', false)
            ->find($sessionExerciseId);

        if (! $current) {
            return;
        }

        $previous = $this->session
            ->workoutSessionExercises()
            ->where('removed', false)
            ->where('order', '<', $current->order)
            ->orderByDesc('order')
            ->first();

        if (! $previous) {
            return;
        }

        $previousOrder = $previous->order;

        $previous->update([
            'order' => $current->order,
        ]);

        $current->update([
            'order' => $previousOrder,
        ]);
    }

    public function moveExerciseDown(int $sessionExerciseId): void
    {
        abort_unless(
            $this->session->user_id === auth()->id(),
            403
        );

        if ($this->session->completed) {
            return;
        }

        $this->reorderExercises();

        $current = $this->session
            ->workoutSessionExercises()
            ->where('removed', false)
            ->find($sessionExerciseId);

        if (! $current) {
            return;
        }

        $next = $this->session
            ->workoutSessionExercises()
            ->where('removed', false)
            ->where('order', '>', $current->order)
            ->orderBy('order')
            ->first();

        if (! $next) {
            return;
        }

        $nextOrder = $next->order;

        $next->update([
            'order' => $current->order,
        ]);

        $current->update([
            'order' => $nextOrder,
        ]);
    }

    protected function reorderExercises(): void
    {
        $items = $this->session
            ->workoutSessionExercises()
            ->where('removed', false)
            ->orderBy('order')
            ->get();

        foreach ($items as $index => $item) {
            $item->update([
                'order' => $index + 1,
            ]);
        }
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
            ->where('removed', false)
            ->with('workoutExercise')
            ->get()
            ->pluck('workoutExercise.exercise_id')
            ->all();

        $sessionExercises = $this->session
            ->workoutSessionExercises()
            ->where('removed', false)
            ->with([
                'workoutExercise.exercise',
                'workoutExercise.workoutExerciseSets',
            ])
            ->orderBy('order')
            ->get();

        return view(
            'livewire.sessions.session-exercises',
            [
                'sessionExercises' => $sessionExercises,
                'exerciseType' => ExerciseType::class,
                'all_muscle_groups' => $allMuscleGroups,
                'available_exercises' => $availableExercises,
                'already_added_exercise_ids' => $alreadyAddedExerciseIds,
            ],
        );
    }
}
