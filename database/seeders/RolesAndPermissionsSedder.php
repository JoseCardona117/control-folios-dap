<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermission();

        $modules = [
            'minutas' => [
                'ver minutas',
                'ver minuta',
                'crear minuta',
                'editar minutas',
                'eliminar minutas',
                'cerrar minutas',
                'reabrir minutas',
            ],

            'minutas_externas' => [
                'ver minutas externas',
                'ver minuta externa',
                'crear minutas externas',
                'editar minutas externas',
                'eliminar minutas externas',
            ],

            'acuerdos' => [
                'ver acuerdos',
                'ver acuerdo',
                'crear acuerdos',
                'editar acuerdos externos',
                'eliminar acuerdos externos',
            ],

            'acuerdos_externos' => [
                'ver acuerdos externos',
                'ver acuerdo externo',
                'crear acuerdos externos',
                'editar acuerdos externos',
                'eliminar acuerdos externos',
            ],
        ];

        foreach($modules as $permission) {
            foreach($permissionsnas as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }
        }

        //Crear Roles

        $roles = [
            'admin',
            'jefe',
            'asistente',
            'empleado'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $admin = Role::findByName('admin');
        $jefe = Role::findByName('jefe');
        $asistente = Role::findByName('asistente');
        $empleado = Role::findByName('empleado');

        $admin->syncPermissions(Permission::all());

        $asistente->syncPermissions(
            collect($modules)
                ->flatten()
                ->reject(fn($perm) => str_contains($perm, 'eliminar'))
                ->values()
        );

        $jefe->syncPermissions(
            collect($modules)
                ->flatten()
                ->reject(fn($perm) => 
                    str_contains($perm, 'crear acuerdos') ||
                    str_contains($perm, 'eliminar')
                )
                ->values()

        );

        $empleado->syncPermissions(
            collect($modules)
                ->flatten()
                ->filter(fn($perm) => str_starts_with($perm, 'ver'))
                ->values()
        );
    }
}
