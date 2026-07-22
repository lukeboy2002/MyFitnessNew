<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateAvatar extends Component
{
    use WithFileUploads;

    public $avatar;

    public function updateAvatar(): void
    {
        $this->authorize('update', Auth::user());

        $this->validate([
            'avatar' => ['required', 'image', 'max:1024'], // 1MB Max
        ]);

        /** @var User $user */
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $this->avatar->store('avatars', 'public');

        $user->update([
            'avatar' => $path,
        ]);

        $this->reset('avatar');
        $this->dispatch('avatar-updated');
        $this->redirect(route('profile.edit'), navigate: true);
    }

    public function render()
    {
        return view('profile.partials.update-avatar', [
            'user' => Auth::user(),
        ]);
    }
}
