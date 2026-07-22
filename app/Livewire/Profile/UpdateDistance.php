<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class UpdateDistance extends Component
{
    public string $distance;

    public function mount(): void
    {
        $this->authorize('update', auth()->user());

        $this->distance = auth()->user()->preferred_distance ?? 'km';
    }

    public function updatedDistance(string $value): void
    {
        $this->authorize('update', auth()->user());

        auth()->user()->update([
            'preferred_distance' => $value,
        ]);

        $this->dispatch('distance-updated', distance: $value);
    }

    public function render()
    {
        return view('profile.partials.update-distance');
    }
}
