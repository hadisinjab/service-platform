<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class FixUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $clientRole = Role::where('name', 'client')->first();
        $providerRole = Role::where('name', 'provider')->first();
        $adminRole = Role::where('name', 'admin')->first();

        // Find users without roles
        $usersWithoutRoles = User::whereDoesntHave('roles')->get();

        foreach ($usersWithoutRoles as $user) {
            // Assign default role based on email or name
            if (str_contains(strtolower($user->email), 'admin')) {
                $user->assignRole($adminRole);
                echo "Assigned admin role to: {$user->email}\n";
            } elseif (str_contains(strtolower($user->email), 'provider')) {
                $user->assignRole($providerRole);
                echo "Assigned provider role to: {$user->email}\n";
            } else {
                // Default to client role
                $user->assignRole($clientRole);
                echo "Assigned client role to: {$user->email}\n";
            }
        }

        echo "Fixed roles for " . $usersWithoutRoles->count() . " users.\n";
    }
}
