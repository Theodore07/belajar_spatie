<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        // app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        
        // create roles and assign existing permissions
     
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = User::factory()->create([
            'name' => 'Base User',
            'email' => 'user@example.com',
            'password' => bcrypt('test123'),
        ]);
        $user->assignRole('writer');

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('test123')
        ]);
        $user->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'Super-Admin User',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('test123')
        ]);
        $user->assignRole('Super-Admin');

        $userSeed = User::factory(10)->create();
        foreach ($userSeed as $user) {
            # code...
            $user->assignRole('writer');
        }

    }
}