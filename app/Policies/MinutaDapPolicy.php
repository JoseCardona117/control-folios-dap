<?php

namespace App\Policies;

use App\Models\MinutaDap;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MinutaDapPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver minutas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MinutaDap $minutaDap): bool
    {
        return $user->can('ver minuta');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear minuta');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MinutaDap $minutaDap): bool
    {
        return $user->can('editar minutas');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MinutaDap $minutaDap): bool
    {
        return $user->can('eliminar minutas');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MinutaDap $minutaDap): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MinutaDap $minutaDap): bool
    {
        return false;
    }
}
