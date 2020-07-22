<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_permissions = Permission::all();
        $admin_permissions = $all_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 18) == 'tenant_management_';
        });
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $tenant_admin_permissions = $all_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 18) != 'tenant_management_';
        });
        Role::findOrFail(2)->permissions()->sync($tenant_admin_permissions);
        $tenant_user_permissions = $all_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 17) == 'asset_management_'
                || substr($permission->title, 0, 17) == 'image_management_'
                || substr($permission->title, 0, 20) == 'document_management_'
                || substr($permission->title, 0, 16) == 'note_management_';
        });
        Role::findOrFail(3)->permissions()->sync($tenant_user_permissions);
    }
}
