<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i                = 0;
        $permissions      = [];
        $permissionGroups = [
            'tenant_management', 'user_management', 'role_management', 'asset_management',
            'asset_group_management', 'image_management', 'document_management', 'note_management',
        ];

        foreach ($permissionGroups as $permissionGroup) {
            foreach (['access', 'create', 'edit', 'show', 'delete'] as $permission) {
                $permissions[] = [
                    'id'    => ++$i,
                    'title' => $permissionGroup . '_' . $permission,
                ];
            }
        }

        Permission::insert($permissions);
    }
}
