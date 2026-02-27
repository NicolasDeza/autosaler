<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard Group
            ['name' => 'post_ads'],
            ['name' => 'view_seller_dashboard'],
            ['name' => 'view_admin_dashboard'],

            // Orders Group
            // ['name' => 'create-orders', 'label' => 'permissions.create_orders', 'group' => 'settings'],
            // ['name' => 'manage-orders', 'label' => 'permissions.manage_orders', 'group' => 'settings'],

        ];

        // Create permissions
        foreach ($permissions as $p) {
            Permission::updateOrCreate(
                $p
            );
        }

        // Create Roles
        $adminRole = Role::updateOrCreate(['name' => 'admin']);
        $sellerRole = Role::updateOrCreate(['name' => 'seller']);
        $user = Role::updateOrCreate(['name' => 'user']);

        $adminRole->syncPermissions([
            'post_ads',
            'view_admin_dashboard'
        ]);

        $sellerRole->syncPermissions([
            'post_ads',
            'view_seller_dashboard'
        ]);


        $this->command->info('Roles, Permissions, and Groups seeded successfully.');
    }
}
