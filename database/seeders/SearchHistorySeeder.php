<?php

namespace Database\Seeders;

use App\Models\PermissionGroups;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SearchHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Історія пошуків' => ['search_history_view', 'search_history_delete'],
        ];

        $permissionIds = Permission::query()->pluck('id')->toArray();

        foreach ($permissions as $groupName => $items) {
            $groupModel = PermissionGroups::create(['name' => $groupName]);

            foreach ($items as $permissionName) {
                $permissionModel = Permission::create([
                    'name'       => $permissionName,
                    'guard_name' => 'web',
                    'group_id'   => $groupModel->id
                ]);

                $permissionIds[] = $permissionModel->id;
            }
        }

        // get role Admin
        $roleModel = Role::query()->first();

        $permissionsModel = Permission::query()->whereIn('id', $permissionIds)->get();

        $roleModel->syncPermissions($permissionsModel);
    }
}
