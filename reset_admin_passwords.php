<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$usersData = [
    'admin@puspamukti.local' => 'Admin2026',
    'kepaladesa@puspamukti.local' => 'Kades2026',
    'sekdes@puspamukti.local' => 'Sekdes2026',
    'bendahara@puspamukti.local' => 'Bendahara2026',
    'admindesa@puspamukti.local' => 'AdminDesa2026',
    'layanan@puspamukti.local' => 'Layanan2026',
];

foreach ($usersData as $email => $password) {
    $user = User::where('email', $email)->first();
    if ($user) {
        $user->password = Hash::make($password);
        $user->save();
        echo "Successfully reset password for {$email} to {$password}\n";
    } else {
        echo "User {$email} not found in database!\n";
    }
}
