<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class FixUserRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create roles
        $clientRole = Role::firstOrCreate(['name' => 'client']);
        $providerRole = Role::firstOrCreate(['name' => 'provider']);

        // Fix client user
        $client = User::where('email', 'client@test.com')->first();
        if ($client) {
            $client->syncRoles([$clientRole]);
            $this->command->info('Client roles fixed: ' . $client->getRoleNames()->implode(', '));
        } else {
            $this->command->error('Client user not found!');
        }

        // Fix provider user
        $provider = User::where('email', 'provider@test.com')->first();
        if ($provider) {
            $provider->syncRoles([$providerRole]);
            $this->command->info('Provider roles fixed: ' . $provider->getRoleNames()->implode(', '));
        } else {
            $this->command->error('Provider user not found!');
        }

        $this->command->info('User roles fixed successfully!');
    }
}
