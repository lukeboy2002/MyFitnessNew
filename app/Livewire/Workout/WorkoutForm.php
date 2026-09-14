<?php

namespace App\Livewire\Workout;

use App\Enum\ExerciseMetric;
use App\Enum\ExerciseType;
use App\Enum\WorkoutSetType;
use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\Workout;
use App\Models\WorkoutExerciseSet;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

class WorkoutForm extends Component
{
    public ?Workout $workout = null;

    #[Rule('required|min:3|max:255')]
    public ?string $name = '';

    #[Rule('required|min:3|max:255')]
    public ?string $slug = '';

    #[Rule('required|boolean')]
    public bool $is_archived = false;

    #[Rule('nullable|array')]
    public array $selectedBodyParts = [];

    public string $searchExercise = '';

    public ?int $filterMuscleGroup = null;

    public ?int $workoutExerciseToDelete = null;

    //    Computed properties
    public function getCardioMetricsProperty(): array
    {
        return ExerciseMetric::cases();
    }

    public function mount(?Workout $workout = null): void
    {
        $this->workout = $workout;

        if ($workout?->exists) {

            $this->authorize('update', $workout);

            $this->name = $workout->name;
            $this->slug = $workout->slug;
            $this->is_archived = $workout->is_archived;

            return;
        }

        $this->authorize('create', Workout::class);
    }

    //    Workout
    public function updatedName(): void
    {
        $this->slug = SlugService::createSlug(
            Workout::class,
            'slug',
            $this->name
        );
    }

    public function save()
    {
        if ($this->workout?->exists) {
            $this->authorize('update', $this->workout);
        } else {
            $this->authorize('create', Workout::class);
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'is_archived' => $this->is_archived,
            'user_id' => $this->workout?->user_id ?? auth()->id(),
        ];

        $workout = $this->workout ?? new Workout;

        $workout->saveItem($data);

        $this->workout = $workout;

        $this->dispatch('workout-saved');

        flash()->success(__('The workout has been saved'));

        return redirect()->route('workout.edit', $workout);
    }

    //    Exercises
    public function addExercise(int $exerciseId): void
    {
        if (! $this->workout?->exists) {
            return;
        }

        $this->authorize('update', $this->workout);

        $exercise = Exercise::visibleTo()
            ->findOrFail($exerciseId);

        $exists = $this->workout
            ->workoutExercises()
            ->where('exercise_id', $exercise->id)
            ->exists();

        if ($exists) {
            flash()->warning(__('This exercise is already added to the workout'));

            return;
        }

        $nextOrder = (
            $this->workout
                ->workoutExercises()
                ->max('order') ?? 0
        ) + 1;

        $workoutExercise = $this->workout
            ->workoutExercises()
            ->create([
                'exercise_id' => $exercise->id,
                'order' => $nextOrder,
            ]);

        //        Cardio default
        if ($exercise->type === ExerciseType::Cardio) {
            $workoutExercise
                ->workoutExerciseSets()
                ->create([
                    'set_number' => 1,
                    'type' => WorkoutSetType::Working,
                    // Strength
                    'target_reps' => null,
                    'target_weight' => null,
                    'rest_seconds' => null,
                    // Cardio
                    'target_duration_seconds' => 1200,
                    'target_distance_km' => null,

                    'target_metric' => null,
                    'target_metric_value' => null,

                    'target_incline_percent' => null,
                ]);
        } else {
            //  Strength default
            for ($i = 1; $i <= 3; $i++) {
                $workoutExercise
                    ->workoutExerciseSets()
                    ->create([
                        'set_number' => $i,
                        'type' => WorkoutSetType::Working,
                        // Strength
                        'target_reps' => 10,
                        'target_weight' => null,
                        'rest_seconds' => 60,
                        // Cardio
                        'target_duration_seconds' => null,
                        'target_distance_km' => null,

                        'target_metric' => null,
                        'target_metric_value' => null,

                        'target_incline_percent' => null,
                    ]);
            }
        }

        flash()->success(__('Exercise added to workout'));
        $this->dispatch('close-modal', 'add-exercise-modal');
    }

    public function deleteExercise(int $workoutExerciseId): void
    {
        $this->workoutExerciseToDelete = $workoutExerciseId;

        $this->dispatch('open-modal', 'delete-exercise');
    }

    public function removeExercise(int $workoutExerciseId): void
    {
        $this->workoutExerciseToDelete =
            $workoutExerciseId;

        $this->confirmDelete();
    }

    public function confirmDelete(): void
    {
        if (! $this->workout?->exists || ! $this->workoutExerciseToDelete) {
            return;
        }

        $this->authorize('update', $this->workout);

        $workoutExercise = $this->workout
            ->workoutExercises()
            ->find(
                $this->workoutExerciseToDelete
            );

        if ($workoutExercise) {
            $workoutExercise->delete();
            $this->reorderExercises();

            flash()->success(__('Exercise removed from workout'));
        }

        $this->dispatch('close-modal', 'delete-exercise');

        $this->workoutExerciseToDelete = null;
    }

    protected function reorderExercises(): void
    {
        if (! $this->workout?->exists) {
            return;
        }

        $items = $this->workout
            ->workoutExercises()
            ->orderBy('order')
            ->get();

        foreach ($items as $index => $item) {
            $item->update([
                'order' => $index + 1,
            ]);
        }
    }

    public function moveExerciseUp(int $workoutExerciseId): void
    {

        if (! $this->workout?->exists) {
            return;
        }

        $this->authorize('update', $this->workout);

        $current = $this->workout
            ->workoutExercises()
            ->find($workoutExerciseId);

        if (! $current) {
            return;
        }

        $previous = $this->workout
            ->workoutExercises()
            ->where(
                'order',
                '<',
                $current->order
            )
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

    public function moveExerciseDown(int $workoutExerciseId): void
    {
        if (! $this->workout?->exists) {
            return;
        }

        $this->authorize('update', $this->workout);

        $current = $this->workout
            ->workoutExercises()
            ->find($workoutExerciseId);

        if (! $current) {
            return;
        }

        $next = $this->workout
            ->workoutExercises()
            ->where(
                'order',
                '>',
                $current->order
            )
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

    public function updateExerciseNotes(int $workoutExerciseId, ?string $notes): void
    {

        if (! $this->workout?->exists) {
            return;
        }

        $this->authorize(
            'update',
            $this->workout
        );

        $workoutExercise = $this->workout
            ->workoutExercises()
            ->find($workoutExerciseId);

        if (! $workoutExercise) {
            return;
        }

        $workoutExercise->update([
            'notes' => $notes,
        ]);

        flash()->success(__('Notes updated'));
    }

    /*
    |--------------------------------------------------------------------------
    | Sets
    |--------------------------------------------------------------------------
    */

    public function addSet(
        int $workoutExerciseId
    ): void {

        if (! $this->workout?->exists) {
            return;
        }

        $this->authorize(
            'update',
            $this->workout
        );

        $workoutExercise = $this->workout
            ->workoutExercises()
            ->with('exercise')
            ->find($workoutExerciseId);

        if (! $workoutExercise) {
            return;
        }

        $nextSetNumber = (
            $workoutExercise
                ->workoutExerciseSets()
                ->max('set_number') ?? 0
        ) + 1;

        $lastSet = $workoutExercise
            ->workoutExerciseSets()
            ->orderByDesc('set_number')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Cardio
        |--------------------------------------------------------------------------
        */

        if (
            $workoutExercise->exercise->type
            === ExerciseType::Cardio
        ) {

            $workoutExercise
                ->workoutExerciseSets()
                ->create([
                    'set_number' => $nextSetNumber,

                    'type' => $lastSet?->type
                        ?? WorkoutSetType::Working,

                    /*
                     * Strength
                     */
                    'target_reps' => null,
                    'target_weight' => null,
                    'rest_seconds' => null,

                    /*
                     * Cardio
                     */
                    'target_duration_seconds' => $lastSet
                        ?->target_duration_seconds
                        ?? 1200,

                    'target_distance_km' => $lastSet
                        ?->target_distance_km,

                    'target_metric' => $lastSet
                        ?->target_metric,

                    'target_metric_value' => $lastSet
                        ?->target_metric_value,

                    'target_incline_percent' => $lastSet
                        ?->target_incline_percent,
                ]);

        } else {

            /*
            |--------------------------------------------------------------------------
            | Strength
            |--------------------------------------------------------------------------
            */

            $workoutExercise
                ->workoutExerciseSets()
                ->create([
                    'set_number' => $nextSetNumber,

                    'type' => $lastSet?->type
                        ?? WorkoutSetType::Working,

                    /*
                     * Strength
                     */
                    'target_reps' => $lastSet
                        ?->target_reps
                        ?? 10,

                    'target_weight' => $lastSet
                        ?->target_weight,

                    'rest_seconds' => $lastSet
                        ?->rest_seconds
                        ?? 60,

                    /*
                     * Cardio
                     */
                    'target_duration_seconds' => null,
                    'target_distance_km' => null,

                    'target_metric' => null,
                    'target_metric_value' => null,

                    'target_incline_percent' => null,
                ]);
        }

        flash()->success(
            __('Set added')
        );
    }

    public function removeSet(
        int $setId
    ): void {

        if (! $this->workout?->exists) {
            return;
        }

        $this->authorize(
            'update',
            $this->workout
        );

        $set = WorkoutExerciseSet::query()
            ->whereHas(
                'workoutExercise',
                fn ($query) => $query->where(
                    'workout_id',
                    $this->workout->id
                )
            )
            ->find($setId);

        if (! $set) {
            return;
        }

        $workoutExercise = $set->workoutExercise;

        $set->delete();

        $sets = $workoutExercise
            ->workoutExerciseSets()
            ->orderBy('set_number')
            ->get();

        foreach ($sets as $index => $item) {

            $item->update([
                'set_number' => $index + 1,
            ]);
        }

        flash()->success(
            __('Set removed')
        );
    }

    public function updateSet(
        int $setId,
        string $field,
        mixed $value
    ): void {

        if (! $this->workout?->exists) {
            return;
        }

        $this->authorize(
            'update',
            $this->workout
        );

        $allowed = [

            /*
             * General
             */
            'type',

            /*
             * Strength
             */
            'target_reps',
            'target_weight',
            'rest_seconds',

            /*
             * Cardio
             */
            'target_duration_seconds',
            'target_duration_minutes',
            'target_distance_km',

            'target_metric',
            'target_metric_value',

            'target_incline_percent',
        ];

        if (
            ! in_array(
                $field,
                $allowed,
                true
            )
        ) {
            return;
        }

        $set = WorkoutExerciseSet::query()
            ->whereHas(
                'workoutExercise',
                fn ($query) => $query->where(
                    'workout_id',
                    $this->workout->id
                )
            )
            ->find($setId);

        if (! $set) {
            return;
        }

        /*
         * Empty value
         */
        $value = $value === ''
            ? null
            : $value;

        /*
         * WorkoutSetType
         */
        if (
            $field === 'type' &&
            is_string($value)
        ) {

            $value = WorkoutSetType::tryFrom($value)
                ?? WorkoutSetType::Working;
        }

        /*
         * ExerciseMetric
         */
        if (
            $field === 'target_metric'
        ) {

            $value = $value !== null
                ? ExerciseMetric::tryFrom($value)
                : null;
        }

        /*
 * Cardio duration
 *
 * Input is entered in minutes,
 * but stored as seconds.
 */
        if (
            $field === 'target_duration_minutes'
        ) {
            $field = 'target_duration_seconds';

            $value = $value !== null
                ? (int) $value * 60
                : null;
        }

        /*
         * Integers
         */
        if (
            in_array(
                $field,
                [
                    'target_duration_seconds',
                    'target_reps',
                    'rest_seconds',
                ],
                true
            ) &&
            $value !== null
        ) {

            $value = (int) $value;
        }

        /*
         * Decimal values
         */
        if (
            in_array(
                $field,
                [
                    'target_weight',
                    'target_distance_km',
                    'target_metric_value',
                    'target_incline_percent',
                ],
                true
            ) &&
            $value !== null
        ) {

            $value = (float) $value;
        }

        $set->update([
            $field => $value,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete workout
    |--------------------------------------------------------------------------
    */

    public function deleteItem(
        Workout $workout
    ): void {

        $this->workout = $workout;

        $this->dispatch(
            'open-modal',
            'delete-workout'
        );
    }

    public function confirmDeleteWorkout()
    {
        if (! $this->workout) {
            return;
        }

        $this->authorize(
            'delete',
            $this->workout
        );

        $this->workout->delete();

        flash()->success(
            __('The workout has been deleted')
        );

        $this->dispatch(
            'workout-deleted'
        );

        $this->dispatch(
            'close-modal',
            'delete-workout'
        );

        $this->workout = null;

        return redirect()->route(
            'workout.index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    #[Layout('layouts.app', ['pageTitle' => 'Workout'])]
    public function render(): View
    {
        $bodyParts = BodyPart::orderBy('name')->get();

        $muscleGroups = MuscleGroup::orderBy('name')->get();

        $workoutExercises = collect();

        $availableExercises = collect();

        $alreadyAddedExerciseIds = [];

        if ($this->workout?->exists) {

            $workoutExercises = $this->workout
                ->workoutExercises()
                ->with([
                    'exercise.bodyParts',
                    'exercise.muscleGroups',
                    'workoutExerciseSets',
                ])
                ->orderBy('order')
                ->get();

            $alreadyAddedExerciseIds = $workoutExercises
                ->pluck('exercise_id')
                ->all();

            $query = Exercise::visibleTo()
                ->with([
                    'bodyParts',
                    'muscleGroups',
                ])
                ->orderBy('name');

            if (
                filled($this->searchExercise)
            ) {

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
        }

        return view(
            'livewire.workout.workout-form',
            [

                'body_parts' => $bodyParts
                    ->map(
                        fn ($bodyPart) => [
                            'id' => $bodyPart->id,
                            'name' => $bodyPart->name,

                            'image' => $bodyPart->image_path
                                ? Storage::url(
                                    $bodyPart->image_path
                                )
                                : null,
                        ]
                    ),

                'all_body_parts' => $bodyParts,
                'all_muscle_groups' => $muscleGroups,
                'workout_exercises' => $workoutExercises,
                'available_exercises' => $availableExercises,
                'already_added_exercise_ids' => $alreadyAddedExerciseIds,
                'set_types' => WorkoutSetType::cases(),
                'cardio_metrics' => ExerciseMetric::cases(),
            ]
        );
    }
}
