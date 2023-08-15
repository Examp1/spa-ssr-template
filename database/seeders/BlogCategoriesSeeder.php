<?php

namespace Database\Seeders;

use App\Models\PermissionGroups;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BlogCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Блог -> Категории'              => ['blog_category_view', 'blog_category_create', 'blog_category_edit', 'blog_category_delete'],
            'Настройки -> Блог -> Категории' => ['setting_blogcategories_view', 'setting_blogcategories_edit'],
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
