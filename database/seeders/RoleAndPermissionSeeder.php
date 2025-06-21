<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Service management
            'view services',
            'create services',
            'edit services',
            'delete services',

            // Order management
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'approve orders',
            'reject orders',

            // Provider management
            'view providers',
            'create providers',
            'edit providers',
            'delete providers',
            'approve providers',
            'reject providers',

            // Client management
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',

            // Review management
            'view reviews',
            'create reviews',
            'edit reviews',
            'delete reviews',

            // Message management
            'view messages',
            'send messages',
            'delete messages',

            // Report management
            'view reports',
            'create reports',
            'export reports',

            // System settings
            'view settings',
            'edit settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'admin' => [
                'view users', 'edit users',
                'view services', 'create services', 'edit services', 'delete services',
                'view orders', 'edit orders', 'approve orders', 'reject orders',
                'view providers', 'edit providers', 'approve providers', 'reject providers',
                'view clients', 'edit clients',
                'view reviews', 'edit reviews', 'delete reviews',
                'view messages', 'send messages', 'delete messages',
                'view reports', 'create reports', 'export reports',
                'view settings', 'edit settings',
            ],
            'provider' => [
                'view services', 'create services', 'edit services',
                'view orders', 'edit orders',
                'view reviews',
                'view messages', 'send messages',
                'view reports',
            ],
            'client' => [
                'view services',
                'view orders', 'create orders', 'edit orders',
                'view reviews', 'create reviews', 'edit reviews',
                'view messages', 'send messages',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
