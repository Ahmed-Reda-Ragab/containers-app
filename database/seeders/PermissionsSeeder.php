<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{

    /**
     * php artisan db:seed --class=PermissionsSeeder
     * List of models to create permissions for
     */
    private array $models = [
        'car',
        'customer',
        'container',
        'contract',
        'offer',
        'payment',
        'employee',
        'receipt',
        'type',
        'contract-container-fill',
        'user',
        'role',
        'report',
    ];

    /**
     * CRUD operations
     */
    private array $operations = [
        'list',
        'create',
        'edit',
        'delete',
        'show',
    ];

    /**
     * Additional permissions (not following the standard CRUD pattern)
     */
    private array $additionalPermissions = [
        // Home
        'home.dashboard',

        // Container additional permissions
        'container.bulk-create',
        'container.bulk-store',

        // Customer additional permissions
        'customer.data',
        'customer.search',

        // Contract additional permissions
        'contract.print',

        // Contract Container Fill additional permissions
        'contract-container-fill.filled',
        'contract-container-fill.discharge',

        // Report additional permissions
        'report.print',

        // Receipt additional permissions
        'receipt.collect',
        'receipt.print',

        // Filled Container permissions
        'filled-container.list',
        'filled-container.mark-filled',
        'filled-container.discharge',
        'filled-container.assign',

        // Offer additional permissions
        'offer.search',

        // User additional permissions
        'user.search',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for all models
        foreach ($this->models as $model) {
            foreach ($this->operations as $operation) {
                $permissionName = "{$model}.{$operation}";

                Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web'],
                    ['name' => $permissionName, 'guard_name' => 'web']
                );
            }
        }

        // Create additional permissions
        foreach ($this->additionalPermissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web'],
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        $this->command->info('Permissions created successfully.');

        // Create or get super admin role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            [
                'name' => 'super-admin',
                'guard_name' => 'web',
                'title' => 'Super Admin',
            ]
        );

        // Assign all permissions to super admin role
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        $this->command->info('Super Admin role created and assigned all permissions.');

        // Create or get admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'phone' => '0000000000',
                'password' => Hash::make('password'),
                'user_type' => \App\Enums\UserType::ADMIN,
            ]
        );

        // Assign super admin role to admin user
        $adminUser->assignRole($superAdminRole);

        $this->command->info('Admin user created/updated with email: admin@admin.com');
        $this->command->info('Default password: password');
    }
}
