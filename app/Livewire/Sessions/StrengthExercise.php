<?php

namespace App\Livewire\Sessions;

use App\Models\WorkoutExercise;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class StrengthExercise extends Component
{
    public WorkoutSession $session;

    public WorkoutExercise $workoutExercise;

    /**
     * @var array<int, array{
     *     workout_set_id: ?int,
     *     weight: mixed,
     *     reps: mixed,
     *     completed: bool
     * }>
     */
    public array $sets = [];

    public ?float $highestScore = null;

    public ?float $newHighScoreValue = null;

    /**
     * @var array<int, string>
     */
    public array $previousSessionSets = [];

    public function mount(): void
    {
        $this->workoutExercise->loadMissing([
            'exercise',
            'workoutExerciseSets',
        ]);

        $this->highestScore = $this->getHighestScore();
        $this->loadPreviousSessionSets();

        foreach ($this->workoutExercise->workoutExerciseSets as $templateSet) {

            $workoutSet = WorkoutSet::query()
                ->where('workout_session_id', $this->session->id)
                ->where('workout_exercise_set_id', $templateSet->id)
                ->first()
                ?? WorkoutSet::create([
                    'workout_session_id' => $this->session->id,
                    'workout_exercise_set_id' => $templateSet->id,
                    'completed' => false,
                ]);

            $this->sets[$templateSet->id] = [
                'workout_set_id' => $workoutSet?->id,
                'weight' => $workoutSet?->weight ?? $templateSet->target_weight,
                'reps' => $workoutSet?->reps ?? $templateSet->target_reps,
                'completed' => $workoutSet?->completed ?? false,
            ];
        }
    }

    public function getHighestScore(): ?float
    {
        $max = WorkoutSet::query()
            ->where('completed', true)
            ->whereNotNull('weight')
            ->where('weight', '>', 0)
            ->whereHas('workoutSession', fn ($query) => $query->where('user_id', $this->session->user_id))
            ->whereHas('workoutExerciseSet.workoutExercise', fn ($query) => $query->where('exercise_id', $this->workoutExercise->exercise_id))
            ->max('weight');

        return $max !== null ? (float) $max : null;
    }

    private function loadPreviousSessionSets(): void
    {
        $this->previousSessionSets = [];

        foreach ($this->workoutExercise->workoutExerciseSets as $templateSet) {
            $previousSet = WorkoutSet::query()
                ->where('workout_exercise_set_id', $templateSet->id)
                ->where('workout_session_id', '!=', $this->session->id)
                ->where('completed', true)
                ->whereHas('workoutSession', fn ($query) => $query->where('user_id', $this->session->user_id))
                ->latest('id')
                ->first();

            if ($previousSet && $previousSet->weight !== null) {
                $this->previousSessionSets[$templateSet->id] = ((float) $previousSet->weight).' kg';
            } elseif ($previousSet && $previousSet->reps !== null) {
                $this->previousSessionSets[$templateSet->id] = $previousSet->reps.' reps';
            } else {
                $this->previousSessionSets[$templateSet->id] = '-';
            }
        }
    }

    public function completeSet(int $templateSetId): void
    {
        if (! isset($this->sets[$templateSetId])) {
            return;
        }

        $data = $this->sets[$templateSetId];

        $workoutSet = WorkoutSet::query()
            ->where('workout_session_id', $this->session->id)
            ->where('workout_exercise_set_id', $templateSetId)
            ->first();

        if (! $workoutSet) {
            return;
        }

        $previousHighest = $this->highestScore ?? $this->getHighestScore();

        $weight = ($data['weight'] ?? '') !== '' ? (float) $data['weight'] : null;
        $reps = ($data['reps'] ?? '') !== '' ? (int) $data['reps'] : null;

        $workoutSet->update([
            'weight' => $weight,
            'reps' => $reps,
            'completed' => true,
        ]);

        $this->sets[$templateSetId]['completed'] = true;

        $newHighest = $this->getHighestScore();
        $this->highestScore = $newHighest;

        $templateSet = $this->workoutExercise
            ->workoutExerciseSets
            ->firstWhere('id', $templateSetId);

        $restSeconds = $templateSet?->rest_seconds ?? 60;
        $this->dispatch('start-rest-timer', seconds: (int) $restSeconds);

        if ($weight !== null && $weight > 0 && ($previousHighest === null || $weight > $previousHighest)) {
            $this->newHighScoreValue = $weight;
            $this->dispatch('open-modal', 'congratulations-pr-strength-'.$this->workoutExercise->id);
        }
    }

    public function render(): View
    {
        return view('sessions.partials.strength-exercise');
    }
}
