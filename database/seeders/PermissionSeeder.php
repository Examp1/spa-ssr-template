<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\PermissionGroups;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Admins'                         => ['admins_view', 'admins_create', 'admins_edit', 'admins_delete'],
            'Roles'                          => ['roles_view', 'roles_create', 'roles_edit', 'roles_delete'],
            'Permissions Groups'             => ['permission_groups_view', 'permission_groups_create', 'permission_groups_edit', 'permission_groups_delete'],
            'Permissions'                    => ['permissions_view', 'permissions_create', 'permissions_edit', 'permissions_delete'],
            'Pages'                          => ['pages_view', 'pages_create', 'pages_edit', 'pages_delete'],
            'Landings'                       => ['landing_view', 'landing_create', 'landing_edit', 'landing_delete'],
            'Services'                       => ['services_view', 'services_create', 'services_edit', 'services_delete'],
            'Blog -> Articles'               => ['blog_articles_view', 'blog_articles_create', 'blog_articles_edit', 'blog_articles_delete'],
            'Blog -> Tags'                   => ['blog_tags_view', 'blog_tags_create', 'blog_tags_edit', 'blog_tags_delete'],
            'Blog -> Categories'             => ['blog_category_view', 'blog_category_create', 'blog_category_edit', 'blog_category_delete'],
            'Blog -> Subscriptions'          => ['blog_subscribe_view', 'blog_subscribe_export'],
            'FAQ'                            => ['faq_view', 'faq_create', 'faq_edit', 'faq_delete'],
            'Widgets'                        => ['widgets_view', 'widgets_create', 'widgets_edit', 'widgets_delete'],
            'Menu'                           => ['menu_view', 'menu_create', 'menu_edit', 'menu_delete'],
            'Settings -> General'            => ['setting_main_view', 'setting_main_edit'],
            'Settings -> Contacts'           => ['setting_contacts_view', 'setting_contacts_edit'],
            'Settings -> Blog'               => ['setting_blog_view', 'setting_blog_edit'],
            'Settings -> Blog -> Categories' => ['setting_blogcategories_view', 'setting_blogcategories_edit'],
            'Settings -> Blog -> Tags'       => ['setting_blogtags_view', 'setting_blogtags_edit'],
            'Settings -> Pages'              => ['setting_page_view', 'setting_page_edit'],
            'Settings -> Landings'           => ['setting_landing_view', 'setting_landing_edit'],
            'Settings -> Services'           => ['setting_services_view', 'setting_services_edit'],
            'Settings -> Theme'              => ['setting_theme_view', 'setting_theme_edit'],
            'Multimedia'                     => ['multimedia_view'],
            'Forms'                          => ['forms_view', 'forms_create', 'forms_edit', 'forms_delete'],
            'Users'                          => ['users_view', 'users_create', 'users_edit', 'users_delete'],
        ];

        $permissionIds = [];

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

        // create role Admin
        $roleModel = Role::create([
            'name'       => 'Admin',
            'guard_name' => 'web',
        ]);

        $permissionsModel = Permission::query()->whereIn('id', $permissionIds)->get();
        $roleModel->syncPermissions($permissionsModel);

        $user = Admin::query()->first();
        $user->syncRoles(['Admin']);
    }
}
