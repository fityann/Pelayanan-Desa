<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::all();
echo "Total users in database: " . $users->count() . "\n\n";

foreach ($users as $user) {
    if ($user->hasRole(['Super Admin', 'Admin Desa', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara'])) {
        echo "Name: " . $user->name . "\n";
        echo "Email: " . $user->email . "\n";
        echo "NIK: " . $user->nik . "\n";
        echo "Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
        // Check password matching for common defaults
        $matched = 'unknown';
        $passwords = [
            'Admin2026', 'Kades2026', 'Sekdes2026', 'Bendahara2026', 'AdminDesa2026', 'Layanan2026', 'password', 'admin', '12345678', '123456'
        ];
        foreach ($passwords as $p) {
            if (Hash::check($p, $user->password)) {
                $matched = $p;
                break;
            }
        }
        echo "Password matches default: " . $matched . "\n";
        echo "---------------------------------\n";
    }
}
