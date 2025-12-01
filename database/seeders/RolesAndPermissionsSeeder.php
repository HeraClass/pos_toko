<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'admin' => [
                'dashboard.view',
                'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
                'products.view', 'products.create', 'products.edit', 'products.delete',
                'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
                'cart.view', 'cart.create', 'cart.edit', 'cart.delete',
                'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
                'suppliers.view', 'suppliers.create', 'suppliers.edit', 'suppliers.delete',
                'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.delete',
                'adjustments.view', 'adjustments.create', 'adjustments.edit', 'adjustments.delete',
                'settings.view', 'settings.edit',
                'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
                'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
                'users.view', 'users.create', 'users.edit', 'users.delete',
            ],

            'cashier' => [
                'dashboard.view',
                'categories.view',
                'products.view',
                'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
                'cart.view', 'cart.create', 'cart.edit', 'cart.delete',
                'orders.view', 'orders.create',
                'suppliers.view',
                'purchases.view',
                'adjustments.view', 'adjustments.create', 'adjustments.edit',
                'settings.view',
            ],
        ];

        foreach ($data as $roleName => $permissions) {

            // Create role
            $role = Role::firstOrCreate(['name' => $roleName]);

            foreach ($permissions as $permissionName) {

                // Create permission if not exists
                $permission = Permission::firstOrCreate(['name' => $permissionName]);

                // Assign permission to role
                if (!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
