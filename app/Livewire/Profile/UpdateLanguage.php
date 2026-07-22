<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class UpdateLanguage extends Component
{
    public string $language;

    public function mount(): void
    {
        $this->authorize('update', auth()->user());

        $this->language = auth()->user()->preferred_language ?? 'en';
    }

    public function updatedLanguage(string $value): void
    {
        $this->authorize('update', auth()->user());

        auth()->user()->update([
            'preferred_language' => $value,
        ]);

        $this->dispatch('language-updated', language: $value);
    }

    public function render()
    {
        return view('profile.partials.update-language');
    }
}
