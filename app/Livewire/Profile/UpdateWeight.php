<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class UpdateWeight extends Component
{
    public string $weight;

    public function mount(): void
    {
        $this->authorize('update', auth()->user());

        $this->weight = auth()->user()->preferred_weight ?? 'kg';
    }

    public function updatedWeight(string $value): void
    {
        $this->authorize('update', auth()->user());

        auth()->user()->update([
            'preferred_weight' => $value,
        ]);

        $this->dispatch('weight-updated', weight: $value);
    }

    public function render()
    {
        return view('profile.partials.update-weight');
    }
}
