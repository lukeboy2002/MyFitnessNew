<?php

namespace App\Livewire\Exercises;

use App\Models\Exercise;
use App\Models\WorkoutSet;
use App\Services\ExerciseMetricFormatter;
use Illuminate\Support\Collection;
use Livewire\Component;

class HighScores extends Component
{
    public Exercise $exercise;

    public ?string $metric = null;

    public function mount(Exercise $exercise): void
    {
        $this->exercise = $exercise;

        $metrics = $this->getMetrics();

        $this->metric = array_key_first($metrics);
    }

    /**
     * Available metrics for this exercise.
     */
    public function getMetrics(): array
    {
        return $this->exercise->type->value === 'strength'
            ? $this->getStrengthMetrics()
            : $this->getCardioMetrics();
    }

    /**
     * Strength metrics.
     */
    protected function getStrengthMetrics(): array
    {
        return [
            'weight' => __('Heaviest Weight'),
            'volume' => __('Best Volume'),
            'reps' => __('Most Reps'),
            'one_rep_max' => __('Estimated 1RM'),
        ];
    }

    /**
     * Cardio metrics.
     */
    protected function getCardioMetrics(): array
    {
        $sets = $this->getExerciseSets();

        $metrics = [];

        if ($sets->whereNotNull('distance_km')->isNotEmpty()) {
            $metrics['distance'] = __('Longest Distance');
        }

        if ($sets->whereNotNull('duration_seconds')->isNotEmpty()) {
            $metrics['duration'] = __('Longest Duration');
        }

        if ($sets->whereNotNull('calories_total')->isNotEmpty()) {
            $metrics['calories'] = __('Most Calories');
        }

        if ($sets->whereNotNull('avg_speed')->isNotEmpty()) {
            $metrics['speed'] = __('Highest Speed');
        }

        if ($sets->whereNotNull('pace_seconds')->isNotEmpty()) {
            $metrics['pace'] = __('Best Pace');
        }

        if ($sets->whereNotNull('avg_heart_rate')->isNotEmpty()) {
            $metrics['heart_rate'] = __('Highest Heart Rate');
        }

        if ($sets->whereNotNull('watts')->isNotEmpty()) {
            $metrics['watts'] = __('Highest Watts');
        }

        return $metrics;
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
                fn ($query) => $query
                    ->where(
                        'exercise_id',
                        $this->exercise->id
                    )
            )

            ->with([
                'workoutSession.workout',
                'workoutExerciseSet.workoutExercise.exercise',
            ])

            ->get();
    }

    /**
     * Change selected metric.
     */
    public function setMetric(string $metric): void
    {
        if (! array_key_exists($metric, $this->getMetrics())) {
            return;
        }

        $this->metric = $metric;
    }

    public function render(
        ExerciseMetricFormatter $formatter
    ) {
        return view(
            'livewire.exercises.partials.high-scores',
            [
                'metrics' => $this->getMetrics(),
                'highScores' => $this->getHighScores(),
                'formatter' => $formatter,
            ]
        );
    }

    /**
     * Get high scores.
     */
    public function getHighScores(): Collection
    {
        $sets = $this->getExerciseSets();

        if ($sets->isEmpty()) {
            return collect();
        }

        $scores = $sets
            ->groupBy('workout_session_id')

            ->map(function (Collection $sessionSets) {

                $session = $sessionSets
                    ->first()
                    ->workoutSession;

                $value = $this->calculateSessionValue(
                    $sessionSets
                );

                return [
                    'value' => $value,

                    'metric' => $this->metric,

                    'date' => $session
                        ?->completed_at
                        ?->format('d M Y'),

                    'timestamp' => $session
                        ?->completed_at
                        ?->timestamp,

                    'workout' => $session
                        ?->workout
                        ?->name,
                ];
            })

            ->filter(
                fn (array $score) => $score['value'] > 0
            );

        return $this->sortScores($scores)
            ->take(5)
            ->values();
    }

    /**
     * Calculate the score for one workout session.
     */
    protected function calculateSessionValue(
        Collection $sets
    ): float {
        return match ($this->metric) {

            /*
             * Strength
             */

            'weight' => (float) (
                $sets
                    ->whereNotNull('weight')
                    ->max('weight')
                ?? 0
            ),

            'volume' => (float) $sets->sum(
                fn (WorkoutSet $set) => (float) $set->weight *
                    (int) $set->reps
            ),

            'reps' => (float) $sets->sum(
                fn (WorkoutSet $set) => (int) $set->reps
            ),

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
                    )

                ?? 0
            ),

            /*
             * Cardio
             */

            'distance' => (float) $sets->sum(
                fn (WorkoutSet $set) => (float) $set->distance_km
            ),

            'duration' => (float) $sets->sum(
                fn (WorkoutSet $set) => (int) $set->duration_seconds
            ),

            'calories' => (float) $sets->sum(
                fn (WorkoutSet $set) => (int) $set->calories_total
            ),

            'speed' => (float) (
                $sets
                    ->whereNotNull('avg_speed')
                    ->max('avg_speed')
                ?? 0
            ),

            /*
             * Lower pace is better.
             */
            'pace' => (float) (
                $sets
                    ->whereNotNull('pace_seconds')
                    ->min('pace_seconds')
                ?? 0
            ),

            'heart_rate' => (float) (
                $sets
                    ->whereNotNull('avg_heart_rate')
                    ->max('avg_heart_rate')
                ?? 0
            ),

            'watts' => (float) (
                $sets
                    ->whereNotNull('watts')
                    ->max('watts')
                ?? 0
            ),

            default => 0,
        };
    }

    /**
     * Sort scores.
     */
    protected function sortScores(
        Collection $scores
    ): Collection {
        if ($this->metric === 'pace') {
            return $scores->sortBy('value');
        }

        return $scores->sortByDesc('value');
    }
}
