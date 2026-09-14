<?php

namespace App\Livewire\Exercises;

use App\Models\Exercise;
use App\Models\WorkoutSet;
use App\Services\ExerciseMetricFormatter;
use Illuminate\Support\Collection;
use Livewire\Component;

class Overview extends Component
{
    public Exercise $exercise;

    public function mount(Exercise $exercise): void
    {
        $this->exercise = $exercise;
    }

    public function render(
        ExerciseMetricFormatter $formatter
    ) {
        $sets = $this->getExerciseSets();

        $isStrength =
            $this->exercise->type->value === 'strength';

        return view(
            'livewire.exercises.partials.overview',
            [
                'sets' => $sets,

                'isStrength' => $isStrength,

                'statistics' => $isStrength
                    ? $this->getStrengthStatistics($sets)
                    : $this->getCardioStatistics($sets),

                'latestSession' => $this->latestSession($sets),

                'formatter' => $formatter,
            ]
        );
    }

    /**
     * Get all completed sets for this exercise.
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
            ])

            ->get();
    }

    /**
     * Strength overview statistics.
     */
    protected function getStrengthStatistics(
        Collection $sets
    ): array {
        return [

            'sessions' => $this->totalSessions($sets),

            'volume' => (float) $sets->sum(
                fn (WorkoutSet $set) => (float) $set->weight *
                    (int) $set->reps
            ),

            'max_weight' => (float) (
                $sets
                    ->whereNotNull('weight')
                    ->max('weight') ?? 0
            ),

            'total_reps' => (int) $sets->sum('reps'),

            'one_rep_max' => (float) (
                $sets
                    ->filter(
                        fn (WorkoutSet $set) => $set->weight !== null &&
                            $set->reps !== null &&
                            $set->reps > 0
                    )
                    ->max(
                        fn (WorkoutSet $set) => (float) $set->weight *
                            (
                                1 +
                                (
                                    (int) $set->reps / 30
                                )
                            )
                    ) ?? 0
            ),

            'total_sets' => $sets->count(),
        ];
    }

    /**
     * Total number of sessions.
     */
    protected function totalSessions(
        Collection $sets
    ): int {
        return $sets
            ->pluck('workout_session_id')
            ->unique()
            ->count();
    }

    /**
     * Cardio overview statistics.
     */
    protected function getCardioStatistics(
        Collection $sets
    ): array {
        return [

            'sessions' => $this->totalSessions($sets),

            'distance' => (float) $sets->sum(
                fn (WorkoutSet $set) => (float) $set->distance_km
            ),

            'duration' => (int) $sets->sum(
                fn (WorkoutSet $set) => (int) $set->duration_seconds
            ),

            'calories' => (int) $sets->sum(
                fn (WorkoutSet $set) => (int) $set->calories_total
            ),

            'avg_speed' => (float) (
                $sets
                    ->whereNotNull('avg_speed')
                    ->avg('avg_speed') ?? 0
            ),

            'avg_heart_rate' => (int) (
                $sets
                    ->whereNotNull('avg_heart_rate')
                    ->avg('avg_heart_rate') ?? 0
            ),

            'max_heart_rate' => (int) (
                $sets
                    ->whereNotNull('max_heart_rate')
                    ->max('max_heart_rate') ?? 0
            ),

            'avg_watts' => (int) (
                $sets
                    ->whereNotNull('watts')
                    ->avg('watts') ?? 0
            ),

            'total_sets' => $sets->count(),
        ];
    }

    /**
     * Get latest completed session.
     */
    protected function latestSession(
        Collection $sets
    ): ?array {
        $sessionSets = $sets
            ->groupBy('workout_session_id')
            ->sortByDesc(function (Collection $sets) {

                return $sets
                    ->first()
                    ?->workoutSession
                    ?->completed_at
                    ?->timestamp ?? 0;
            })
            ->first();

        if (! $sessionSets) {
            return null;
        }

        $session = $sessionSets
            ->first()
            ?->workoutSession;

        if (! $session) {
            return null;
        }

        return [
            'session' => $session,
            'sets' => $sessionSets,
        ];
    }
}
