<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

echo "=== TESTING REAL CITIZEN COMPLAINT SUBMISSION ===\n\n";

$controller = new \App\Http\Controllers\WargaRtController();
$request = Request::create('/rt/01/pengaduan', 'POST', [
    'nama' => 'Budi Santoso (Warga RT 01)',
    'whatsapp' => '081234567890',
    'kategori' => 'air',
    'judul' => 'Kesulitan air bersih di RT 01 saat memasak',
    'deskripsi' => 'Saya sering kesulitan air saat sedang memasak di dapur, mohon bantuan perbaikan saluran air.',
]);

try {
    $response = $controller->createPengaduan($request, '01');
    echo "[OK] Response status: " . $response->getStatusCode() . "\n";
    echo "[OK] Response body: " . $response->getContent() . "\n";

    // Verify record in DB
    $latest = \App\Models\Pengaduan::latest()->first();
    echo "[OK] DB Record ID: " . $latest->id . "\n";
    echo "[OK] Tiket ID: " . $latest->tiket_id . "\n";
    echo "[OK] Pelapor: " . $latest->nama_pelapor . "\n";
    echo "[OK] Judul: " . $latest->judul . "\n";
    echo "[OK] Status: " . $latest->status . "\n";

    // Verify Notification
    $notif = \App\Models\Notification::latest()->first();
    echo "[OK] Notif Staff: " . $notif->judul . " - " . $notif->pesan . "\n";
} catch (\Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
