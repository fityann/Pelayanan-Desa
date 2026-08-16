<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    App\Models\Penduduk::create([
        'nik' => '9999888877776666',
        'nama' => 'Test',
        'jenis_kelamin' => 'L',
        'kewarganegaraan' => 'WNI',
        'alamat' => 'Test'
    ]);
    echo "Success!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
