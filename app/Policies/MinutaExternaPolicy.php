<?php

namespace App\Policies;

use App\Models\MinutaExterna;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MinutaExternaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver minutas externas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MinutaExterna $minutaExterna): bool
    {
        return $user->can('ver minuta externa');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear minutas externas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MinutaExterna $minutaExterna): bool
    {
        return $user->can('editar minutas externas');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MinutaExterna $minutaExterna): bool
    {
        return $user->can('eliminar minutas externas');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MinutaExterna $minutaExterna): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MinutaExterna $minutaExterna): bool
    {
        return false;
    }
}
