<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

echo "=== CHECKING PERMISSIONS FOR PENGADUAN ===\n\n";

$permU = Permission::where('name', 'U Pengaduan')->first();
echo "Permission 'U Pengaduan' exists: " . ($permU ? 'YES' : 'NO') . "\n";

$roles = Role::all();
foreach ($roles as $role) {
    $has = $role->hasPermissionTo('U Pengaduan') ? 'YES' : 'NO';
    echo "Role '{$role->name}': has 'U Pengaduan' = $has\n";
}

echo "\n--- USERS CHECK ---\n";
$users = User::all();
foreach ($users as $u) {
    $userRoles = $u->getRoleNames()->join(', ');
    $canProses = $u->can('U Pengaduan') ? 'YES' : 'NO';
    echo "User ID {$u->id} ({$u->name}) [Roles: $userRoles] - Can 'U Pengaduan': $canProses\n";
}
