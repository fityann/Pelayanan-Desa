<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING WargaChatController ===\n\n";

$controller = new \App\Http\Controllers\Warga\ChatController();
$request = Request::create('/rt/01/chat', 'POST', [
    'isi' => 'Tes pesan dari warga',
]);

// Log in as warga
$warga = \App\Models\User::first();
Auth::guard('warga')->login($warga);

try {
    $response = $controller->kirim($request, '01', '01');
    echo "[OK] Response status: " . $response->getStatusCode() . "\n";
    echo "[OK] Response body: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
