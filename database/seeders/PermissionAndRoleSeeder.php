<?php 

namespace Database\Seeders;

use App\Enums\AppPermissions;
use App\Enums\AppRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAndRoleSeeder extends Seeder
{
    public function run(): void
    {
        $_permissions = collect(AppPermissions::cases());
        
        $permissions = $_permissions->map(function ($permission) {
            return ['name' => $permission->value, 'guard_name' => 'api'];
        })->toArray();

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission['name'],
            ], $permission);
        }

        Role::updateOrCreate(
            ['name' => AppRoles::SUPER_ADMIN->value], 
            ['name' => AppRoles::SUPER_ADMIN->value, 'guard_name' => 'api']
        );  
        Role::updateOrCreate(
            ['name' => AppRoles::ADMIN->value], 
            ['name' => AppRoles::ADMIN->value, 'guard_name' => 'api']
        );
        Role::updateOrCreate(
            ['name' => AppRoles::USER->value], 
            ['name' => AppRoles::USER->value, 'guard_name' => 'api']
        );
    }
}