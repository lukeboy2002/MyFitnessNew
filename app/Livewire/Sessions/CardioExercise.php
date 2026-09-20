<?php

declare(strict_types=1);

namespace App\Livewire\Sessions;

use App\Models\WorkoutExercise;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;

class CardioExercise extends Component
{
    /**
     * Cardio metrics in priority order.
     *
     * @var array<string, array{suffix: string, cast: 'float'|'int'|'time'}>
     */
    private const METRIC_PRIORITY = [
        'distance_km' => ['suffix' => ' km', 'cast' => 'float'],
        'watts' => ['suffix' => ' W', 'cast' => 'int'],
        'avg_speed' => ['suffix' => ' km/h', 'cast' => 'float'],
        'duration_seconds' => ['suffix' => '', 'cast' => 'time'],
        'calories_total' => ['suffix' => ' kcal', 'cast' => 'int'],
    ];

    public WorkoutSession $session;

    public WorkoutExercise $workoutExercise;

    public ?int $workoutSetId = null;

    public bool $completed = false;

    public ?string $highestScore = null;

    public ?string $newHighScoreValue = null;

    /**
     * Previous session results per template set.
     *
     * @var array<int, string>
     */
    public array $previousSessionSets = [];

    /*
     * Cardio results
     */

    public ?string $duration = null;

    public ?float $distanceKm = null;

    public ?int $caloriesTotal = null;

    public ?int $caloriesActive = null;

    public ?float $mets = null;

    public ?int $watts = null;

    public ?float $inclineDegrees = null;

    public ?int $strokeRate = null;

    public ?string $pace = null;

    public ?int $floors = null;

    public ?int $rotations = null;

    public ?float $avgSpeed = null;

    public ?int $avgHeartRate = null;

    public ?int $maxHeartRate = null;

    /**
     * Splits, keyed by split number.
     *
     * @var array<int, array{distance: float|null, duration: string|null}>
     */
    public array $splits = [];

    /**
     * The workout set currently being edited.
     */
    private ?WorkoutSet $currentSet = null;

    public function mount(): void
    {
        $this->workoutExercise->loadMissing([
            'exercise',
            'workoutExerciseSets',
            'workoutExerciseSets.workoutSets',
            'workoutExerciseSets.workoutSets.splits',
        ]);

        $this->highestScore = $this->getHighestScoreText();

        $this->loadPreviousSessionSets();

        $templateSet = $this->workoutExercise
            ->workoutExerciseSets
            ->first();

        if (! $templateSet) {
            return;
        }

        $workoutSet = $templateSet->workoutSets
            ->firstWhere(
                'workout_session_id',
                $this->session->id
            )
            ?? WorkoutSet::create([
                'workout_session_id' => $this->session->id,
                'workout_exercise_set_id' => $templateSet->id,
                'completed' => false,
            ]);

        $workoutSet->loadMissing('splits');

        $this->loadWorkoutSet($workoutSet);
    }

    /*
     * High score
     */

    public function getHighestScoreText(): ?string
    {
        return $this->formatBestMetric(
            $this->completedSetsForExercise()->get()
        );
    }

    private function formatBestMetric(
        Collection $sets
    ): ?string {
        foreach (self::METRIC_PRIORITY as $column => $meta) {
            $max = $sets
                ->where($column, '>', 0)
                ->max($column);

            if ($max !== null) {
                return $this->formatMetricValue(
                    $max,
                    $meta
                );
            }
        }

        return null;
    }

    /**
     * @param array{
     *     suffix: string,
     *     cast: 'float'|'int'|'time'
     * } $meta
     */
    private function formatMetricValue(
        int|float|string $value,
        array $meta
    ): string {
        return match ($meta['cast']) {
            'time' => $this->secondsToTime(
                (int) $value
            ) ?? '',

            'int' => ((int) $value)
                .$meta['suffix'],

            default => ((float) $value)
                .$meta['suffix'],
        };
    }

    /*
     * Previous session
     */

    private function secondsToTime(
        ?int $seconds
    ): ?string {
        if ($seconds === null) {
            return null;
        }

        return sprintf(
            '%d:%02d',
            intdiv($seconds, 60),
            $seconds % 60
        );
    }

    /*
     * Load workout set
     */

    private function completedSetsForExercise(
        ?int $excludeSetId = null
    ): Builder {
        return WorkoutSet::query()
            ->where('completed', true)
            ->when(
                $excludeSetId,
                fn ($query) => $query->where(
                    'id',
                    '!=',
                    $excludeSetId
                )
            )
            ->whereHas(
                'workoutSession',
                fn ($query) => $query->where(
                    'user_id',
                    $this->session->user_id
                )
            )
            ->whereHas(
                'workoutExerciseSet.workoutExercise',
                fn ($query) => $query->where(
                    'exercise_id',
                    $this->workoutExercise->exercise_id
                )
            );
    }

    /*
     * Save results
     */

    private function loadPreviousSessionSets(): void
    {
        $this->previousSessionSets = [];

        foreach (
            $this->workoutExercise->workoutExerciseSets as $templateSet
        ) {
            $previousSet = WorkoutSet::query()
                ->where(
                    'workout_exercise_set_id',
                    $templateSet->id
                )
                ->where(
                    'workout_session_id',
                    '!=',
                    $this->session->id
                )
                ->where('completed', true)
                ->whereHas(
                    'workoutSession',
                    fn ($query) => $query->where(
                        'user_id',
                        $this->session->user_id
                    )
                )
                ->latest('id')
                ->first();

            $this->previousSessionSets[
            $templateSet->id
            ] = $previousSet
                ? (
                    $this->formatBestMetric(
                        collect([$previousSet])
                    )
                    ?? '-'
                )
                : '-';
        }
    }

    /*
     * Complete exercise
     */

    private function loadWorkoutSet(
        WorkoutSet $workoutSet
    ): void {
        $this->workoutSetId = $workoutSet->id;

        $this->completed = $workoutSet->completed;

        $this->duration = $this->secondsToTime(
            $workoutSet->duration_seconds
        );

        $this->distanceKm = $workoutSet->distance_km !== null
            ? (float) $workoutSet->distance_km
            : null;
        $this->caloriesTotal = $workoutSet->calories_total !== null
            ? (int) $workoutSet->calories_total
            : null;
        $this->caloriesActive = $workoutSet->calories_active !== null
            ? (int) $workoutSet->calories_active
            : null;
        $this->mets = $workoutSet->mets !== null
            ? (float) $workoutSet->mets
            : null;
        $this->watts = $workoutSet->watts !== null
            ? (int) $workoutSet->watts
            : null;
        $this->inclineDegrees = $workoutSet->incline_percent !== null
            ? (float) $workoutSet->incline_percent
            : null;
        $this->strokeRate = $workoutSet->stroke_rate !== null
            ? (int) $workoutSet->stroke_rate
            : null;
        $this->pace = $this->secondsToTime(
            $workoutSet->pace_seconds
        );

        $this->floors = $workoutSet->floors;

        $this->rotations = $workoutSet->rotations;

        $this->avgSpeed = $workoutSet->avg_speed !== null
            ? (float) $workoutSet->avg_speed
            : null;
        $this->avgHeartRate = $workoutSet->avg_heart_rate !== null
            ? (int) $workoutSet->avg_heart_rate
            : null;
        $this->maxHeartRate = $workoutSet->max_heart_rate !== null
            ? (int) $workoutSet->max_heart_rate
            : null;
        $this->splits = [];

        foreach ($workoutSet->splits as $split) {
            $this->splits[
            $split->split_number
            ] = [
                'distance' => $split->distance_km,
                'duration' => $this->secondsToTime(
                    $split->duration_seconds
                ),
            ];
        }
    }

    public function completeExercise(): void
    {
        if ($this->completed) {
            return;
        }

        $workoutSet = $this->currentWorkoutSet();

        if (! $workoutSet) {
            return;
        }

        /*
         * Get previous completed sets BEFORE
         * completing the current exercise.
         */
        $priorSets = $this
            ->completedSetsForExercise(
                $workoutSet->id
            )
            ->get();

        $this->saveResults();

        $workoutSet->refresh();

        $prText = $this->determineNewPr(
            $workoutSet,
            $priorSets
        );

        $workoutSet->update([
            'completed' => true,
        ]);

        $this->completed = true;

        $this->highestScore = $this->getHighestScoreText();

        if ($prText !== null) {
            $this->showPr($prText);
        }
    }

    /*
     * Personal record detection
     */

    private function currentWorkoutSet(): ?WorkoutSet
    {
        if (! $this->workoutSetId) {
            return null;
        }

        return $this->currentSet ??=
            WorkoutSet::find(
                $this->workoutSetId
            );
    }

    public function saveResults(): void
    {
        $workoutSet = $this->currentWorkoutSet();

        if (! $workoutSet) {
            return;
        }

        $durationSeconds = $this->timeToSeconds(
            $this->duration
        );

        $paceSeconds = $this->timeToSeconds(
            $this->pace
        );

        $priorSets = $this
            ->completedSetsForExercise(
                $workoutSet->id
            )
            ->get();

        $workoutSet->update([
            'duration_seconds' => $durationSeconds,
            'distance_km' => $this->distanceKm ?: null,

            'mets' => $this->mets ?: null,
            'watts' => $this->watts ?: null,

            'calories_total' => $this->caloriesTotal ?: null,
            'calories_active' => $this->caloriesActive ?: null,

            'incline_percent' => $this->inclineDegrees ?: null,
            'stroke_rate' => $this->strokeRate ?: null,

            'pace_seconds' => $paceSeconds,

            'floors' => $this->floors ?: null,
            'rotations' => $this->rotations ?: null,

            'avg_speed' => $this->avgSpeed ?: null,

            'avg_heart_rate' => $this->avgHeartRate ?: null,
            'max_heart_rate' => $this->maxHeartRate ?: null,
        ]);

        $this->saveSplits($workoutSet);

        $this->highestScore = $this->getHighestScoreText();

        /*
         * Only check for a PR here when editing
         * an already completed exercise.
         */
        if ($this->completed) {
            $prText = $this->determineNewPr(
                $workoutSet->refresh(),
                $priorSets
            );

            if ($prText !== null) {
                $this->showPr($prText);
            }
        }

        flash()->success(
            __('Cardio results saved')
        );
    }

    /*
     * Splits
     */

    private function timeToSeconds(
        ?string $time
    ): ?int {
        if (
            ! $time
            || ! str_contains($time, ':')
        ) {
            return null;
        }

        $parts = explode(':', $time);

        if (count($parts) !== 2) {
            return null;
        }

        [$minutes, $seconds] = array_map(
            'intval',
            $parts
        );

        if (
            $minutes < 0
            || $seconds < 0
            || $seconds > 59
        ) {
            return null;
        }

        return ($minutes * 60)
            + $seconds;
    }

    private function saveSplits(
        WorkoutSet $workoutSet
    ): void {
        $workoutSet
            ->splits()
            ->delete();

        foreach ($this->splits as $splitNumber => $split) {
            if (
                empty($split['distance'])
                || empty($split['duration'])
            ) {
                continue;
            }

            $splitDurationSeconds = $this->timeToSeconds(
                $split['duration']
            );

            if ($splitDurationSeconds === null) {
                continue;
            }

            $workoutSet
                ->splits()
                ->create([
                    'split_number' => (int) $splitNumber,
                    'distance_km' => $split['distance'],
                    'duration_seconds' => $splitDurationSeconds,
                    'pace_seconds' => null,
                ]);
        }
    }

    private function determineNewPr(
        WorkoutSet $set,
        Collection $priorSets
    ): ?string {
        foreach (
            self::METRIC_PRIORITY as $column => $meta
        ) {
            $value = $set->{$column};

            if (
                empty($value)
                || $value <= 0
            ) {
                continue;
            }

            $prevMax = $priorSets
                ->where($column, '>', 0)
                ->max($column);

            if (
                $prevMax === null
                || $value > $prevMax
            ) {
                return $this->formatMetricValue(
                    $value,
                    $meta
                );
            }
        }

        return null;
    }

    private function showPr(
        string $prText
    ): void {
        $this->newHighScoreValue = $prText;

        $this->dispatch(
            'open-modal',
            'congratulations-pr-cardio-'
            .$this->workoutExercise->id
        );
    }

    /*
     * Current workout set
     */

    public function saveSplit(
        int $splitNumber
    ): void {
        $split = $this->splits[
        $splitNumber
        ] ?? null;

        if (
            ! $split
            || empty($split['distance'])
            || empty($split['duration'])
        ) {
            return;
        }

        $durationSeconds = $this->timeToSeconds(
            $split['duration']
        );

        if ($durationSeconds === null) {
            return;
        }

        $workoutSet = $this->currentWorkoutSet();

        if (! $workoutSet) {
            return;
        }

        $workoutSet
            ->splits()
            ->updateOrCreate(
                [
                    'split_number' => $splitNumber,
                ],
                [
                    'distance_km' => $split['distance'],
                    'duration_seconds' => $durationSeconds,
                    'pace_seconds' => null,
                ]
            );
    }

    /*
     * Time helpers
     */

    public function addSplit(): void
    {
        $nextNumber = empty($this->splits)
            ? 1
            : max(array_keys($this->splits)) + 1;

        $this->splits[
        $nextNumber
        ] = [
            'distance' => 1,
            'duration' => '',
        ];
    }

    public function removeSplit(
        int $splitNumber
    ): void {
        unset(
            $this->splits[$splitNumber]
        );

        $this->splits = collect($this->splits)
            ->values()
            ->mapWithKeys(
                fn ($split, $index) => [
                    $index + 1 => $split,
                ]
            )
            ->all();

        $workoutSet = $this->currentWorkoutSet();

        if ($workoutSet) {
            $this->saveSplits($workoutSet);
        }
    }

    public function render(): View
    {
        return view(
            'sessions.partials.cardio-exercise'
        );
    }
}
