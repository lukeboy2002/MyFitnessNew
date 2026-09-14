<?php

namespace App\Livewire\Exercises;

use App\Models\Exercise;
use App\Models\WorkoutSet;
use App\Services\ExerciseMetricFormatter;
use Illuminate\Support\Collection;
use Livewire\Component;

class ProgressChart extends Component
{
    public Exercise $exercise;

    public string $metric;

    public function mount(
        Exercise $exercise
    ): void {
        $this->exercise = $exercise;

        $metrics = $this->getMetrics();

        $this->metric = array_key_first($metrics);
    }

    /**
     * Available metrics.
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
            'volume' => __('Volume'),
            'weight' => __('Weight'),
            'reps' => __('Reps'),
            'one_rep_max' => __('1RM'),
        ];
    }

    /**
     * Cardio metrics based on available data.
     */
    protected function getCardioMetrics(): array
    {
        $sets = $this->getExerciseSets();

        $metrics = [];

        if ($sets->whereNotNull('distance_km')->isNotEmpty()) {
            $metrics['distance'] = __('Distance');
        }

        if ($sets->whereNotNull('duration_seconds')->isNotEmpty()) {
            $metrics['duration'] = __('Duration');
        }

        if ($sets->whereNotNull('calories_total')->isNotEmpty()) {
            $metrics['calories'] = __('Calories');
        }

        if ($sets->whereNotNull('avg_speed')->isNotEmpty()) {
            $metrics['speed'] = __('Speed');
        }

        if ($sets->whereNotNull('pace_seconds')->isNotEmpty()) {
            $metrics['pace'] = __('Pace');
        }

        if ($sets->whereNotNull('avg_heart_rate')->isNotEmpty()) {
            $metrics['heart_rate'] = __('Heart Rate');
        }

        if ($sets->whereNotNull('watts')->isNotEmpty()) {
            $metrics['watts'] = __('Watts');
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
                'workoutSession',
                'workoutExerciseSet.workoutExercise.exercise',
            ])

            ->get();
    }

    /**
     * Change selected metric.
     */
    public function setMetric(string $metric): void
    {
        if (! array_key_exists(
            $metric,
            $this->getMetrics()
        )) {
            return;
        }

        $this->metric = $metric;

        $progressData =
            $this->getProgressData();

        $this->dispatch(
            'progress-chart-updated',

            chartData: $this->getChartData(
                $progressData
            )
        );
    }

    /**
     * Progress data grouped by workout session.
     */
    public function getProgressData(): Collection
    {
        $sets = $this->getExerciseSets();

        return collect(
            $sets
                ->groupBy('workout_session_id')
                ->map(function (
                    Collection $sessionSets
                ) {

                    $session = $sessionSets
                        ->first()
                        ->workoutSession;

                    $value = $this->calculateSessionValue(
                        $sessionSets
                    );

                    return [
                        'date' => $session->completed_at
                            ?->format('d M'),

                        'timestamp' => $session->completed_at
                            ?->timestamp,

                        'value' => $value,
                    ];
                })
                ->filter(
                    fn (array $item) => $item['value'] > 0
                )
                ->sortBy('timestamp')
                ->values()
        );
    }

    /**
     * Calculate value for one workout session.
     */
    protected function calculateSessionValue(
        Collection $sets
    ): float {
        return match ($this->metric) {

            /*
             * Strength
             */

            'volume' => (float) $sets->sum(
                fn ($set) => (float) $set->weight *
                    (int) $set->reps
            ),

            'weight' => (float) (
                $sets->max(
                    fn ($set) => (float) $set->weight
                ) ?? 0
            ),

            'reps' => (float) $sets->sum(
                fn ($set) => (int) $set->reps
            ),

            'one_rep_max' => (float) (
                $sets
                    ->filter(
                        fn ($set) => $set->weight !== null &&
                            $set->reps !== null &&
                            $set->reps > 0
                    )
                    ->max(
                        fn ($set) => (float) $set->weight *
                            (
                                1 +
                                (
                                    (int) $set->reps / 30
                                )
                            )
                    ) ?? 0
            ),

            /*
             * Cardio
             */

            'distance' => (float) $sets->sum(
                fn ($set) => (float) $set->distance_km
            ),

            'duration' => (float) $sets->sum(
                fn ($set) => (int) $set->duration_seconds
            ),

            'calories' => (float) $sets->sum(
                fn ($set) => (int) $set->calories_total
            ),

            'speed' => (float) (
                $sets
                    ->whereNotNull('avg_speed')
                    ->avg('avg_speed') ?? 0
            ),

            'pace' => (float) (
                $sets
                    ->whereNotNull('pace_seconds')
                    ->avg('pace_seconds') ?? 0
            ),

            'heart_rate' => (float) (
                $sets
                    ->whereNotNull('avg_heart_rate')
                    ->avg('avg_heart_rate') ?? 0
            ),

            'watts' => (float) (
                $sets
                    ->whereNotNull('watts')
                    ->avg('watts') ?? 0
            ),

            default => 0,
        };
    }

    /**
     * Prepare data for Chart.js.
     */
    protected function getChartData(
        ?Collection $progressData = null
    ): array {
        $progressData ??= $this->getProgressData();

        return [
            'labels' => $progressData
                ->pluck('date')
                ->values()
                ->all(),

            'values' => $progressData
                ->pluck('value')
                ->values()
                ->all(),
        ];
    }

    public function render(
        ExerciseMetricFormatter $formatter
    ) {
        $progressData = $this->getProgressData();

        return view(
            'livewire.exercises.partials.progress-chart',
            [
                'metrics' => $this->getMetrics(),

                'progressData' => $progressData,

                'chartData' => $this->getChartData(
                    $progressData
                ),

                'formatter' => $formatter,
            ]
        );
    }
}
