<?php

namespace App\Policies;

use App\Models\Exercise;
use App\Models\User;

class ExercisePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Exercise $exercise): bool
    {
        if ($user->is_admin) {
            return $exercise->user_id === null;
        }

        return $exercise->user_id === null || $exercise->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Exercise $exercise): bool
    {
        if ($exercise->user_id !== null) {
            return $user->id === $exercise->user_id;
        }

        return (bool) $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Exercise $exercise): bool
    {
        if ($exercise->user_id !== null) {
            return $user->id === $exercise->user_id;
        }

        return (bool) $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Exercise $exercise): bool
    {
        if ($exercise->user_id !== null) {
            return $user->id === $exercise->user_id;
        }

        return (bool) $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Exercise $exercise): bool
    {
        if ($exercise->user_id !== null) {
            return $user->id === $exercise->user_id;
        }

        return (bool) $user->is_admin;
    }
}
