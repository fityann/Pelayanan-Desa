<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== SYNCING ALL PENGADUAN PERMISSIONS TO STAFF ROLES ===\n\n";

$permissions = ['R Pengaduan', 'C Pengaduan', 'U Pengaduan', 'D Pengaduan'];
foreach ($permissions as $p) {
    Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
}

$staffRoles = ['Super Admin', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara', 'Admin Desa'];
foreach ($staffRoles as $roleName) {
    $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    $role->givePermissionTo($permissions);
    echo "[OK] Role '$roleName' granted all Pengaduan permissions.\n";
}

echo "\nSUCCESS! All village staff roles can now view, process, and manage citizen complaints.\n";
