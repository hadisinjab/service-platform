<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

echo "=== Role System Test ===\n\n";

// Check roles
echo "Roles in system:\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "- {$role->name}\n";
}

echo "\nUsers and their roles:\n";
$users = User::with('roles')->get();
foreach ($users as $user) {
    $roleNames = $user->roles->pluck('name')->implode(', ');
    echo "- {$user->name} ({$user->email}): {$roleNames}\n";
}

echo "\n=== Test Complete ===\n";
