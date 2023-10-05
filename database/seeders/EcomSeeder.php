<?php

namespace Database\Seeders;

use App\Models\PermissionGroups;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EcomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Categories'                     => ['categories_view', 'categories_create', 'categories_edit', 'categories_delete'],
            'Products'                       => ['products_view', 'products_create', 'products_edit', 'products_delete'],
            'Filter'                         => ['filters_view', 'filters_create', 'filters_edit', 'filters_delete'],
            'Attribute Groups'               => ['attribute_groups_view', 'attribute_groups_create', 'attribute_groups_edit', 'attribute_groups_delete'],
            'Attributes'                     => ['attributes_view', 'attributes_create', 'attributes_edit', 'attributes_delete'],
            'Orders'                         => ['orders_view', 'orders_create', 'orders_edit', 'orders_delete'],
            'Coupons'                        => ['coupons_view', 'coupons_create', 'coupons_edit', 'coupons_delete'],
            'Discounts'                      => ['discounts_view', 'discounts_create', 'discounts_edit', 'discounts_delete'],
            'Reviews'                        => ['reviews_view', 'reviews_create', 'reviews_edit', 'reviews_delete'],
            'Settings -> Categories'         => ['setting_categories_view', 'setting_categories_edit'],
            'Settings -> Products'           => ['setting_products_view', 'setting_products_edit'],
            'Settings -> Checkout'           => ['setting_checkout_view', 'setting_checkout_edit'],
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
