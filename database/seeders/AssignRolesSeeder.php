<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AssignRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Usamos ID institucional
        $adminIds = [210318,9520];
        $assistenteIds = [38960,247395];
        $jefeIds = [9393,98901,99606,89142];

        $roles = Role::pluck('name')->toArray();

        foreach (User::all() as $user) {
            
            if (in_array($user->id_uaa, $adminIds)) {
                $user->syncRoles('admin');
            } elseif (in_array($user->id_uaa, $assistenteIds)) {
                $user->syncRoles('asistente');
            } elseif (in_array($user->id_uaa, $jefeIds)) {
                $user->syncRoles('jefe');
            } else {
                $user->syncRoles('empleado');
            }
        }
    }
}
