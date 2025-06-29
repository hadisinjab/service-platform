<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles and permissions first
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        // Create default users with roles
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $provider = User::create([
            'name' => 'Provider User',
            'email' => 'provider@example.com',
            'password' => bcrypt('password'),
        ]);
        $provider->assignRole('provider');

        $client = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
        ]);
        $client->assignRole('client');

        // Create comprehensive test data
        $this->call([
            TestDataSeeder::class,
        ]);
    }
}
