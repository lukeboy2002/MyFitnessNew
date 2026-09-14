<?php

namespace App\Services;

use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Illuminate\Support\Collection;

class ExerciseStatisticsService
{
    /**
     * Calculate all statistics for an exercise
     * within a single completed workout session.
     */
    public function forSession(
        WorkoutSession $session,
        int $exerciseId
    ): array {
        $session->loadMissing([
            'workoutSets.workoutExerciseSet.workoutExercise',
        ]);

        $sets = $session
            ->workoutSets
            ->filter(
                fn (WorkoutSet $set) => $set
                    ->workoutExerciseSet
                    ?->workoutExercise
                    ?->exercise_id === $exerciseId
            )
            ->filter(
                fn (WorkoutSet $set) => $set->completed
            )
            ->values();

        return $this->forSets($sets);
    }

    /**
     * Calculate all statistics for a collection of workout sets.
     */
    public function forSets(Collection $sets): array
    {
        if ($sets->isEmpty()) {
            return [];
        }

        return [
            /*
             * General
             */
            'sets' => $sets->count(),

            /*
             * Strength
             */
            'volume' => $this->volume($sets),
            'max_weight' => $this->maxWeight($sets),
            'total_reps' => $this->totalReps($sets),
            'estimated_1rm' => $this->estimated1RM($sets),

            /*
             * Cardio
             */
            'distance' => $this->distance($sets),
            'duration' => $this->duration($sets),
            'calories' => $this->calories($sets),
            'avg_speed' => $this->averageSpeed($sets),
            'pace' => $this->averagePace($sets),
            'avg_heart_rate' => $this->averageHeartRate($sets),
            'max_heart_rate' => $this->maxHeartRate($sets),
            'avg_watts' => $this->averageWatts($sets),
            'incline_percent' => $this->averageIncline($sets),
            'stroke_rate' => $this->averageStrokeRate($sets),
            'floors' => $this->floors($sets),
            'rotations' => $this->rotations($sets),
            'mets' => $this->averageMets($sets),
        ];
    }

    /**
     * Total training volume.
     *
     * Weight × reps for all completed sets.
     */
    protected function volume(Collection $sets): float
    {
        return (float) $sets->sum(
            fn (WorkoutSet $set) => (float) ($set->weight ?? 0)
                * (int) ($set->reps ?? 0)
        );
    }

    /**
     * Heaviest completed set.
     */
    protected function maxWeight(Collection $sets): float
    {
        return (float) (
            $sets
                ->whereNotNull('weight')
                ->max('weight') ?? 0
        );
    }

    /**
     * Total number of completed reps.
     */
    protected function totalReps(Collection $sets): int
    {
        return (int) $sets->sum(
            fn (WorkoutSet $set) => (int) ($set->reps ?? 0)
        );
    }

    /**
     * Estimated one-rep max using the Epley formula.
     */
    protected function estimated1RM(Collection $sets): float
    {
        return (float) (
            $sets
                ->filter(
                    fn (WorkoutSet $set) => $set->weight !== null
                        && $set->reps !== null
                        && $set->reps > 0
                )
                ->map(
                    fn (WorkoutSet $set) => (float) $set->weight
                        * (1 + ((int) $set->reps / 30))
                )
                ->max() ?? 0
        );
    }

    /**
     * Total distance.
     */
    protected function distance(Collection $sets): float
    {
        return (float) $sets->sum(
            fn (WorkoutSet $set) => (float) ($set->distance_km ?? 0)
        );
    }

    /**
     * Total duration in seconds.
     */
    protected function duration(Collection $sets): int
    {
        return (int) $sets->sum(
            fn (WorkoutSet $set) => (int) ($set->duration_seconds ?? 0)
        );
    }

    /**
     * Total calories.
     */
    protected function calories(Collection $sets): int
    {
        return (int) $sets->sum(
            fn (WorkoutSet $set) => (int) ($set->calories_total ?? 0)
        );
    }

    /**
     * Average speed.
     */
    protected function averageSpeed(Collection $sets): float
    {
        $values = $sets
            ->whereNotNull('avg_speed')
            ->map(
                fn (WorkoutSet $set) => (float) $set->avg_speed
            );

        return $values->isNotEmpty()
            ? (float) $values->avg()
            : 0;
    }

    /**
     * Average pace.
     */
    protected function averagePace(Collection $sets): float
    {
        $values = $sets
            ->whereNotNull('pace_seconds')
            ->map(
                fn (WorkoutSet $set) => (float) $set->pace_seconds
            );

        return $values->isNotEmpty()
            ? (float) $values->avg()
            : 0;
    }

    /**
     * Average heart rate.
     */
    protected function averageHeartRate(Collection $sets): int
    {
        $values = $sets
            ->whereNotNull('avg_heart_rate')
            ->map(
                fn (WorkoutSet $set) => (int) $set->avg_heart_rate
            );

        return $values->isNotEmpty()
            ? (int) round($values->avg())
            : 0;
    }

    /**
     * Maximum heart rate.
     */
    protected function maxHeartRate(Collection $sets): int
    {
        return (int) (
            $sets
                ->whereNotNull('max_heart_rate')
                ->max('max_heart_rate') ?? 0
        );
    }

    /**
     * Average watts.
     */
    protected function averageWatts(Collection $sets): int
    {
        $values = $sets
            ->whereNotNull('watts')
            ->map(
                fn (WorkoutSet $set) => (int) $set->watts
            );

        return $values->isNotEmpty()
            ? (int) round($values->avg())
            : 0;
    }

    /**
     * Average incline percentage.
     */
    protected function averageIncline(Collection $sets): float
    {
        $values = $sets
            ->whereNotNull('incline_percent')
            ->map(
                fn (WorkoutSet $set) => (float) $set->incline_percent
            );

        return $values->isNotEmpty()
            ? (float) $values->avg()
            : 0;
    }

    /**
     * Average stroke rate.
     */
    protected function averageStrokeRate(Collection $sets): int
    {
        $values = $sets
            ->whereNotNull('stroke_rate')
            ->map(
                fn (WorkoutSet $set) => (int) $set->stroke_rate
            );

        return $values->isNotEmpty()
            ? (int) round($values->avg())
            : 0;
    }

    /**
     * Total floors.
     */
    protected function floors(Collection $sets): int
    {
        return (int) $sets->sum(
            fn (WorkoutSet $set) => (int) ($set->floors ?? 0)
        );
    }

    /**
     * Total rotations.
     */
    protected function rotations(Collection $sets): int
    {
        return (int) $sets->sum(
            fn (WorkoutSet $set) => (int) ($set->rotations ?? 0)
        );
    }

    /**
     * Average METS.
     */
    protected function averageMets(Collection $sets): float
    {
        $values = $sets
            ->whereNotNull('mets')
            ->map(
                fn (WorkoutSet $set) => (float) $set->mets
            );

        return $values->isNotEmpty()
            ? round((float) $values->avg(), 2)
            : 0;
    }
}
