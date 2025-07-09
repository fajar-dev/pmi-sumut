<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_agenda","view_any_agenda","create_agenda","update_agenda","restore_agenda","restore_any_agenda","delete_agenda","delete_any_agenda","force_delete_agenda","force_delete_any_agenda","view_category","view_any_category","create_category","update_category","delete_category","delete_any_category","force_delete_category","force_delete_any_category","view_gallery","view_any_gallery","create_gallery","update_gallery","restore_gallery","restore_any_gallery","delete_gallery","delete_any_gallery","force_delete_gallery","force_delete_any_gallery","view_infographic","view_any_infographic","create_infographic","update_infographic","restore_infographic","restore_any_infographic","delete_infographic","delete_any_infographic","force_delete_infographic","force_delete_any_infographic","view_menu","view_any_menu","create_menu","update_menu","delete_menu","delete_any_menu","force_delete_menu","force_delete_any_menu","view_message","view_any_message","delete_message","delete_any_message","force_delete_message","force_delete_any_message","restore_message","restore_any_message","view_page","view_any_page","create_page","update_page","restore_page","restore_any_page","delete_page","delete_any_page","force_delete_page","force_delete_any_page","view_partner","view_any_partner","create_partner","update_partner","delete_partner","delete_any_partner","force_delete_partner","force_delete_any_partner","view_post","view_any_post","create_post","update_post","restore_post","restore_any_post","delete_post","delete_any_post","force_delete_post","force_delete_any_post","view_service","view_any_service","create_service","update_service","delete_service","delete_any_service","force_delete_service","force_delete_any_service","view_slider","view_any_slider","create_slider","update_slider","delete_slider","delete_any_slider","force_delete_slider","force_delete_any_slider","view_sub::menu","view_any_sub::menu","create_sub::menu","update_sub::menu","delete_sub::menu","delete_any_sub::menu","force_delete_sub::menu","force_delete_any_sub::menu","view_user","view_any_user","create_user","update_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
