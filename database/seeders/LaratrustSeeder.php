<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laratrust\Models\Role;
use Laratrust\Models\Permission;
use App\Models\User;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Truncating User, Role and Permission tables');

        // Truncate tables if configured
        if (config('laratrust_seeder.truncate_tables') ?? false) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::table('permission_role')->truncate();
            DB::table('permission_user')->truncate();
            DB::table('role_user')->truncate();

            // Use the imported Role and Permission classes
            Role::truncate();
            Permission::truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Create roles
        $administrator = Role::firstOrCreate([
            'name' => 'administrator',
            'display_name' => 'Administrator',
            'description' => 'System Administrator',
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'customer',
            'display_name' => 'Customer',
            'description' => 'Customer',
        ]);

        $vendor = Role::firstOrCreate([
            'name' => 'vendor',
            'display_name' => 'Vendor',
            'description' => 'Vendor',
        ]);

        // Create permissions based on your configuration
        $permissionMap = [
            'users' => ['create', 'read', 'update', 'delete'],
            'payments' => ['create', 'read', 'update', 'delete'],
            'profile' => ['read', 'update'],
            'categories' => ['create', 'read', 'update', 'delete'],
            'products' => ['create', 'read', 'update', 'delete'],
            'orders' => ['create', 'read', 'update', 'delete'],
        ];

        foreach ($permissionMap as $model => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . '-' . strtolower($model),
                    'display_name' => ucfirst($action) . ' ' . ucfirst($model),
                    'description' => ucfirst($action) . ' ' . ucfirst($model),
                ]);
            }
        }

        // Assign permissions to roles based on your config
        $rolesStructure = config('laratrust_seeder.roles_structure', [
            'administrator' => [
                'users' => 'c,r,u,d',
                'payments' => 'c,r,u,d',
                'profile' => 'r,u',
                'categories' => 'c,r,u,d',
                'products' => 'c,r,u,d',
                'orders' => 'r,u',
            ],
            'customer' => [
                'users' => 'c,r,u,d',
                'profile' => 'r,u',
                'categories' => 'r',
                'products' => 'r',
                'orders' => 'c,r,u',
            ],
            'vendor' => [
                'profile' => 'r,u',
            ],
        ]);

        $permissionCodes = [
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
        ];

        foreach ($rolesStructure as $roleName => $modules) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                continue;
            }

            $permissionIds = [];

            foreach ($modules as $module => $value) {
                $permissions = explode(',', $value);

                foreach ($permissions as $p) {
                    $p = trim($p);
                    if (isset($permissionCodes[$p])) {
                        $permissionName = $permissionCodes[$p] . '-' . strtolower($module);
                        $permission = Permission::where('name', $permissionName)->first();

                        if ($permission) {
                            $permissionIds[] = $permission->id;
                        }
                    }
                }
            }

            $role->permissions()->sync(array_unique($permissionIds));
        }

        // Create users if configured
        if (config('laratrust_seeder.create_users') ?? false) {
            $this->createUsers();
        }

        $this->command->info('Laratrust seeding completed successfully.');
    }

    /**
     * Create users for each role.
     */
    private function createUsers(): void
    {
        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Administrator',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $admin->addRole('administrator');

        // Create customer user
        $customer = User::firstOrCreate([
            'email' => 'customer@example.com',
        ], [
            'name' => 'Customer',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $customer->addRole('customer');

        // Create vendor user
        $vendor = User::firstOrCreate([
            'email' => 'vendor@example.com',
        ], [
            'name' => 'Vendor',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $vendor->addRole('vendor');
    }
}
