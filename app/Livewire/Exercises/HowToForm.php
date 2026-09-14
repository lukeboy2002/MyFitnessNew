<?php

namespace App\Livewire\Exercises;

use Livewire\Attributes\Rule;
use Livewire\Component;

class HowToForm extends Component
{
    #[Rule('required|min:3|max:5000')]
    public ?string $howto = '';

    public bool $allowImageUpload = false;

    public function mount(?string $value = null, bool $allowImageUpload = false): void
    {
        $this->howto = $value ?? '';
        $this->allowImageUpload = $allowImageUpload;
    }

    public function render()
    {
        return view('livewire.exercises.partials.how-to-form');
    }
}
