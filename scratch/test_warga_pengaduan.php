<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING WargaPengaduanController ===\n\n";

$controller = new \App\Http\Controllers\Warga\PengaduanController();
$request = Request::create('/pengaduan', 'POST', [
    'judul' => 'Sampah Menumpuk di RT 05',
    'kategori' => 'sampah',
    'deskripsi' => 'Sampah belum diambil berhari-hari',
    'nama' => 'User Test',
    'whatsapp' => '08111111111',
    'sumber_akses' => 'qr_code',
    'lokasi_qr' => 'RT:05|RW:02|Lokasi:Sampah RT 05|Type:complaint',
    'rt' => '05',
    'rw' => '02',
]);

// Log in as warga if necessary
$warga = \App\Models\User::first(); // Just get any user for test if user_id is required
Auth::guard('warga')->login($warga);

try {
    $response = $controller->store($request);
    echo "[OK] Response status: " . $response->getStatusCode() . "\n";
    echo "[OK] Response body: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
