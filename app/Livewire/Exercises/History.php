<?php

namespace App\Livewire\Exercises;

use App\Models\Exercise;
use App\Models\WorkoutSet;
use App\Services\ExerciseMetricFormatter;
use App\Services\ExerciseStatisticsService;
use Illuminate\Support\Collection;
use Livewire\Component;

class History extends Component
{
    public Exercise $exercise;

    public int $perPage = 10;

    public function mount(Exercise $exercise): void
    {
        $this->exercise = $exercise;
    }

    /**
     * Load more history.
     */
    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function render(
        ExerciseStatisticsService $statistics,
        ExerciseMetricFormatter $formatter
    ) {
        $history = $this->getHistory();

        $items = $history
            ->take($this->perPage)

            ->map(function (
                array $item
            ) use (
                $statistics
            ) {

                $session = $item['session'];

                return [
                    ...$item,

                    'statistics' => $statistics->forSession(
                        $session,
                        $this->exercise->id
                    ),
                ];
            });

        return view(
            'livewire.exercises.partials.history',
            [
                'history' => $items,

                'hasMore' => $history->count() > $items->count(),

                'formatter' => $formatter,
            ]
        );
    }

    /**
     * Build history grouped by workout session.
     */
    protected function getHistory(): Collection
    {
        return collect(
            $this->getExerciseSets()

                ->groupBy('workout_session_id')

                ->map(function (Collection $sets) {

                    $session = $sets
                        ->first()
                        ->workoutSession;

                    return [
                        'session' => $session,

                        'date' => $session
                            ?->completed_at,

                        'workout' => $session
                            ?->workout
                            ?->name,

                        'sets' => $sets,
                    ];
                })

                ->sortByDesc(
                    fn (array $item) => $item['date']?->timestamp ?? 0
                )

                ->values()
        );
    }

    /**
     * Get all completed workout sets for this exercise.
     */
    protected function getExerciseSets(): Collection
    {
        return WorkoutSet::query()
            ->where('completed', true)

            ->whereHas(
                'workoutSession',
                fn ($query) => $query
                    ->where('user_id', auth()->id())
                    ->where('completed', true)
            )

            ->whereHas(
                'workoutExerciseSet.workoutExercise',
                fn ($query) => $query->where(
                    'exercise_id',
                    $this->exercise->id
                )
            )

            ->with([
                'workoutSession.workout',

                'workoutSession.workoutSets.workoutExerciseSet.workoutExercise',

                'workoutExerciseSet.workoutExercise.exercise',
            ])

            ->get();
    }
}
