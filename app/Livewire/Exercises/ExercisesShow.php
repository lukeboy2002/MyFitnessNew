<?php

namespace App\Livewire\Exercises;

use App\Models\Exercise;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ExercisesShow extends Component
{
    public Exercise $exercise;

    public $role = '';

    public string $tab = 'overview';

    public function mount(Exercise $exercise): void
    {
        $this->authorize('view', $exercise);

        $this->exercise = $exercise->load([
            'bodyParts',
            'muscleGroups',
            'muscles' => function ($query) {
                $query->orderByRaw("CASE exercise_muscle.role WHEN 'primary' THEN 1 WHEN 'secondary' THEN 2 WHEN 'stabilizer' THEN 3 ELSE 4 END");
            },
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    #[Layout('layouts.app', ['pageTitle' => 'Exercise'])]
    public function render()
    {
        return view('livewire.exercises.exercises-show');
    }
}
