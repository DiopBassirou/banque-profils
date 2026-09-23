<?php

namespace App\Policies;

use App\Models\Profil;
use App\Models\User;

class ProfilPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Profil $profil): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Profil $profil): bool
    {
        if ($user && $user->role === 'admin') {
            return true;
        }

        $magicEmail = request()->attributes->get('magic_email');
        return $magicEmail && $profil->email_gestion === $magicEmail;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Profil $profil): bool
    {
        return $this->update($user, $profil);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Profil $profil): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Profil $profil): bool
    {
        return $user->role === 'admin';
    }
}
