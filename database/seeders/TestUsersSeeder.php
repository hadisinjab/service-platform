<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create test client
        $client = User::create([
            'name' => 'Test Client',
            'email' => 'client@test.com',
            'password' => bcrypt('password'),
            'phone' => '+966501234567',
            'address' => 'Riyadh, Saudi Arabia'
        ]);
        $client->assignRole('client');

        // Create test provider
        $provider = User::create([
            'name' => 'Test Provider',
            'email' => 'provider@test.com',
            'password' => bcrypt('password'),
            'phone' => '+966507654321',
            'address' => 'Jeddah, Saudi Arabia'
        ]);
        $provider->assignRole('provider');

        $this->command->info('Test users created successfully!');
        $this->command->info('Client: client@test.com / password');
        $this->command->info('Provider: provider@test.com / password');
    }
}
