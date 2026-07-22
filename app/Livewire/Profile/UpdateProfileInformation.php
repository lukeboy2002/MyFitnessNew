<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdateProfileInformation extends Component
{
    public string $name = '';

    public string $username = '';

    public string $email = '';

    public function mount(): void
    {
        $this->authorize('update', Auth::user());

        /** @var User $user */
        $user = Auth::user();
        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfileInformation(): void
    {
        $this->authorize('update', Auth::user());

        /** @var User $user */
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique(User::class, 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        session()->flash('status', 'profile-updated');
    }

    public function sendVerification(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    public function render()
    {
        return view('profile.partials.update-profile-information');
    }
}
