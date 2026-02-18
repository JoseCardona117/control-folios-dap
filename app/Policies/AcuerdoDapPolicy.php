<?php

namespace App\Policies;

use App\Models\AcuerdoDap;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AcuerdoDapPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver acuerdos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AcuerdoDap $acuerdoDap): bool
    {
        return $user->can('ver acuerdo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear acuerdos');
    }
    /* Función que se usaría si no se quisiera que se puedan agregar acuerdos una vez cerrada la minuta.
    public function create(User $user, MinutaDap $minuta): bool
    {
        if (!$user->can('crear acuerdos')) {
            return false;
        }

        return $minuta->estatus === 'abierta';
    }*/ 

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AcuerdoDap $acuerdoDap): bool
    {
        return $user->can('editar acuerdos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AcuerdoDap $acuerdoDap): bool
    {
        return $user->can('eliminar acuerdos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AcuerdoDap $acuerdoDap): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AcuerdoDap $acuerdoDap): bool
    {
        return false;
    }

}
