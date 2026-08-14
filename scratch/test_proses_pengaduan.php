<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;

echo "=== TESTING PROSES PENGADUAN ===\n\n";

$pengaduan = Pengaduan::where('status', 'diterima')->first();
if (!$pengaduan) {
    echo "No accepted complaint found to test.\n";
    exit;
}

echo "Found Complaint ID: {$pengaduan->id}, Status: {$pengaduan->status}\n";

$adminUser = User::first();
if ($adminUser) {
    auth()->login($adminUser);
    echo "Logged in as user ID: " . auth()->id() . "\n";
}

$controller = new \App\Http\Controllers\Admin\PengaduanController();
try {
    $response = $controller->proses($pengaduan->id);
    echo "[OK] Response status: " . $response->getStatusCode() . "\n";
    echo "[OK] Response body: " . $response->getContent() . "\n";

    $updated = Pengaduan::find($pengaduan->id);
    echo "[OK] Updated Status: " . $updated->status . "\n";
} catch (\Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
