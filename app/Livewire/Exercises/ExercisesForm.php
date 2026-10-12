<?php

namespace App\Livewire\Exercises;

use App\Enum\ExerciseType;
use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\MuscleGroup;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ExercisesForm extends Component
{
    use WithFileUploads;

    public ?Exercise $exercise = null;

    #[Rule('nullable|exists:users,id')]
    public ?int $user_id = null;

    #[Rule('nullable|image|max:10240|mimes:jpg,jpeg,png,webp,heic,heif')]
    public $image_path;

    #[Rule(['required', new Enum(ExerciseType::class)])]
    public ExerciseType|string|null $type = ExerciseType::Cardio;

    #[Rule('required|min:3|max:255')]
    public ?string $name = '';

    #[Rule('required|min:3|max:255')]
    public ?string $slug = '';

    #[Rule('nullable|min:3|max:255')]
    public ?string $description = '';

    #[Rule('required|min:3|max:5000')]
    public ?string $howto = '';

    #[Rule('required|min:3|max:5000')]
    public ?string $tips = '';

    public array $selectedBodyParts = [];

    public array $selectedMuscleGroups = [];

    public array $selectedMuscles = [];

    public array $muscleRoles = [];

    public function updatedName(): void
    {
        $this->slug = SlugService::createSlug(Exercise::class, 'slug', $this->name ?? '');
    }

    public function mount(?Exercise $exercise = null): void
    {
        $this->exercise = $exercise;

        if ($exercise?->exists) {

            $this->authorize('update', $exercise);

            $this->exercise->load([
                'bodyParts',
                'muscleGroups',
                'muscles',
            ]);

            $this->user_id = $this->exercise->user_id;
            $this->name = $this->exercise->name;
            $this->slug = $this->exercise->slug;
            $this->type = $this->exercise->type ?? ExerciseType::Strength;
            $this->description = $this->exercise->description;
            //            $this->howto = $this->exercise->howto;
            $this->howto = is_array($this->exercise->howto)
                ? implode("\n", $this->exercise->howto)
                : ($this->exercise->howto ?? '');
            $this->tips = is_array($this->exercise->tips)
                ? implode("\n", $this->exercise->tips)
                : ($this->exercise->tips ?? '');

            $this->selectedBodyParts = $this->exercise
                ->bodyParts
                ->pluck('id')
                ->all();

            $this->selectedMuscleGroups = $this->exercise
                ->muscleGroups
                ->pluck('id')
                ->all();

            $this->selectedMuscles = $this->exercise
                ->muscles
                ->pluck('id')
                ->all();

            $this->muscleRoles = $this->exercise
                ->muscles
                ->mapWithKeys(
                    fn (Muscle $muscle) => [
                        $muscle->id => $muscle->pivot->role,
                    ]
                )
                ->all();

        } else {
            $this->authorize('create', Exercise::class);
            $this->user_id = auth()->user()?->is_admin ? null : auth()->id();
        }
    }

    public function save()
    {
        if ($this->exercise?->exists) {
            $this->authorize('update', $this->exercise);
        } else {
            $this->authorize('create', Exercise::class);
        }

        $this->validate();

        $howtoSteps = ! empty($this->howto)
            ? array_values(array_filter(array_map('trim', explode("\n", $this->howto)), fn ($line) => $line !== ''))
            : null;
        $tipsSteps = ! empty($this->tips)
            ? array_values(array_filter(array_map('trim', explode("\n", $this->tips)), fn ($line) => $line !== ''))
            : null;

        $data = [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type instanceof ExerciseType ? $this->type->value : $this->type,
            'description' => $this->description,
            //            'howto' => $this->howto,
            'howto' => $howtoSteps, // Wordt door Eloquent's 'array' cast automatisch als JSON opgeslagen
            'tips' => $tipsSteps, // Wordt door Eloquent's 'array' cast automatisch als JSON opgeslagen

        ];

        $exercise = $this->exercise ?? new Exercise;

        $exercise->saveItem($data, $this->image_path);

        $this->syncBodyParts($exercise);

        $this->syncMuscleGroups($exercise);

        $this->syncMuscles($exercise);

        $this->dispatch('exercise-saved');

        flash()->success(__('The exercise has been saved'));

        return redirect()->route('exercises.index');
    }

    private function syncBodyParts(
        Exercise $exercise
    ): void {
        $exercise
            ->bodyParts()
            ->sync(
                $this->selectedBodyParts
            );
    }

    private function syncMuscleGroups(
        Exercise $exercise
    ): void {
        $exercise
            ->muscleGroups()
            ->sync(
                $this->selectedMuscleGroups
            );
    }

    private function syncMuscles(Exercise $exercise): void
    {

        $data = [];

        foreach ($this->selectedMuscles as $muscleId) {

            $data[$muscleId] = [
                'role' => $this->muscleRoles[$muscleId]
                    ?? 'primary',
            ];
        }

        $exercise
            ->muscles()
            ->sync($data);
    }

    public function updatedSelectedMuscles(): void
    {
        foreach ($this->selectedMuscles as $muscleId) {

            if (
                ! isset(
                    $this->muscleRoles[$muscleId]
                )
            ) {

                $this->muscleRoles[$muscleId] =
                    'primary';
            }
        }

        $this->muscleRoles = array_intersect_key(
            $this->muscleRoles,
            array_flip($this->selectedMuscles)
        );
    }

    #[Layout('layouts.app', ['pageTitle' => 'Exercises'])]
    public function render()
    {
        $bodyParts = BodyPart::orderBy('name')
            ->get()
            ->map(
                fn (BodyPart $bodyPart) => [
                    'id' => $bodyPart->id,
                    'name' => $bodyPart->name,
                ]
            )
            ->values()
            ->all();

        $muscleGroups = MuscleGroup::orderBy('name')
            ->get()
            ->map(
                fn (MuscleGroup $muscleGroup) => [
                    'id' => $muscleGroup->id,
                    'name' => $muscleGroup->name,
                ]
            )
            ->values()
            ->all();

        $muscles = Muscle::orderBy('name')
            ->get()
            ->map(
                fn (Muscle $muscle) => [
                    'id' => $muscle->id,
                    'name' => $muscle->name,
                ]
            )
            ->values()
            ->all();

        return view(
            'livewire.exercises.exercises-form',
            [
                'bodyParts' => $bodyParts,
                'muscleGroups' => $muscleGroups,
                'muscles' => $muscles,
            ]
        );
    }
}
