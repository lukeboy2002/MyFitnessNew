<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class UpdateAppearance extends Component
{
    public string $theme;

    public function mount(): void
    {
        $this->authorize('update', auth()->user());

        $this->theme = auth()->user()->preferred_theme ?? 'system';
    }

    public function updatedTheme(string $value): void
    {
        $this->authorize('update', auth()->user());

        auth()->user()->update([
            'preferred_theme' => $value,
        ]);

        $this->dispatch('appearance-updated', theme: $value);
    }

    public function render()
    {
        return view('profile.partials.update-appearance');
    }
}
