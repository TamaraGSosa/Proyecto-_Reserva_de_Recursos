<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        // 🔄 Limpia la caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
   
        $rol_admin = Role::create(['name' => 'administrador']);
        $rol_staff = Role::create(['name' => 'personal']);

        
        Permission::create(['name' => 'gestionar_recursos'])->assignRole($rol_admin);
        Permission::create(['name' => 'gestionar_reservas'])->syncRoles([$rol_admin,$rol_staff]);
        Permission::create(['name' => 'gestionar_usuarios'])->assignRole($rol_admin);
    }
}
