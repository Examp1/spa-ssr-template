<?php

namespace Database\Seeders;

use App\Models\PermissionGroups;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BlogTagsSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Настройки -> Блог -> Теги' => ['setting_blogtags_view', 'setting_blogtags_edit'],
        ];

        $permissionIds = [];

        // Создаем привилегии с группами
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
    }
}
