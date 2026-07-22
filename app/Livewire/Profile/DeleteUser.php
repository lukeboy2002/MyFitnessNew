<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteUser extends Component
{
    public string $password = '';

    public function deleteUser(): void
    {
        $this->authorize('delete', Auth::user());

        $this->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('profile.partials.delete-user');
    }
}
