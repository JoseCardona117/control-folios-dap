<?php

namespace App\Policies;

use App\Models\AcuerdoExterno;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AcuerdoExternoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver acuerdos externos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AcuerdoExterno $acuerdoExterno): bool
    {
        return $user->can('ver acuerdo externo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear acuerdos externos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AcuerdoExterno $acuerdoExterno): bool
    {
        return $user->can('editar acuerdos externos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AcuerdoExterno $acuerdoExterno): bool
    {
        return $user->can('eliminar acuerdos externos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AcuerdoExterno $acuerdoExterno): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AcuerdoExterno $acuerdoExterno): bool
    {
        return false;
    }
}
