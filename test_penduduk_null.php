<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    App\Models\Penduduk::create([
        'nik' => '9999888877776665',
        'nama' => 'Test',
        'jenis_kelamin' => 'L',
        'kewarganegaraan' => null, // Simulating empty string from form converted to null
        'alamat' => 'Test'
    ]);
    echo "Success!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
