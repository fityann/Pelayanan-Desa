<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$consoleKernel->bootstrap();

$httpKernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Http\Controllers\Admin\SuratController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$superAdmin = User::where('email', 'admin@puspamukti.local')->first();
$kades = User::where('email', 'kepaladesa@puspamukti.local')->first();
$wargaUser = User::where('email', 'warga@puspamukti.local')->first();
$jenisSurat = JenisSurat::where('aktif', true)->first();

echo "=== SURAT MODULE TESTING RESULTS ===\n\n";

// Disable CSRF for testing
$app->instance(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class, new class {
    public function handle($request, $next) { return $next($request); }
});

// 1. Warga Surat Index (Katalog)
Auth::logout();
Auth::guard('warga')->login($wargaUser);
$req = Request::create('/rt/01/surat', 'GET');
$req->setLaravelSession($app['session']->driver());
$req->session()->put('warga_rt', '01');
$req->session()->put('warga_rw', '01');
try {
    $res = $httpKernel->handle($req);
    $status = $res->getStatusCode();
    echo ($status === 200) ? "✅ PASS: Warga Surat Index (HTTP 200)\n" : "❌ FAIL: Warga Surat Index (HTTP {$status})\n";
} catch (\Throwable $e) {
    echo "❌ CRASH: Warga Surat Index - " . $e->getMessage() . "\n";
}

// 2. Warga Surat Riwayat
$req = Request::create('/rt/01/surat/riwayat', 'GET');
$req->setLaravelSession($app['session']->driver());
try {
    $res = $httpKernel->handle($req);
    $status = $res->getStatusCode();
    echo ($status === 200) ? "✅ PASS: Warga Surat Riwayat (HTTP 200)\n" : "❌ FAIL: Warga Surat Riwayat (HTTP {$status})\n";
} catch (\Throwable $e) {
    echo "❌ CRASH: Warga Surat Riwayat - " . $e->getMessage() . "\n";
}

// 3. Warga Create Form for JenisSurat
if ($jenisSurat) {
    $req = Request::create("/rt/01/surat/{$jenisSurat->id}/buat", 'GET');
    $req->setLaravelSession($app['session']->driver());
    try {
        $res = $httpKernel->handle($req);
        $status = $res->getStatusCode();
        echo ($status === 200) ? "✅ PASS: Warga Create Form for '{$jenisSurat->nama}' (HTTP 200)\n" : "❌ FAIL: Warga Create Form (HTTP {$status})\n";
    } catch (\Throwable $e) {
        echo "❌ CRASH: Warga Create Form - " . $e->getMessage() . "\n";
    }
}

// 4. Admin Pengajuan Masuk List
Auth::logout();
Auth::guard('web')->login($superAdmin);
$req = Request::create('/admin/surat/pengajuan', 'GET');
$req->setLaravelSession($app['session']->driver());
try {
    $res = $httpKernel->handle($req);
    $status = $res->getStatusCode();
    echo ($status === 200) ? "✅ PASS: Admin Pengajuan Masuk List (HTTP 200)\n" : "❌ FAIL: Admin Pengajuan Masuk List (HTTP {$status})\n";
} catch (\Throwable $e) {
    echo "❌ CRASH: Admin Pengajuan Masuk List - " . $e->getMessage() . "\n";
}

// 5. Admin Jenis Surat List
$req = Request::create('/admin/surat/jenis', 'GET');
$req->setLaravelSession($app['session']->driver());
try {
    $res = $httpKernel->handle($req);
    $status = $res->getStatusCode();
    echo ($status === 200) ? "✅ PASS: Admin Jenis Surat List (HTTP 200)\n" : "❌ FAIL: Admin Jenis Surat List (HTTP {$status})\n";
} catch (\Throwable $e) {
    echo "❌ CRASH: Admin Jenis Surat List - " . $e->getMessage() . "\n";
}

// 6. Admin Arsip Surat List
$req = Request::create('/admin/surat/arsip', 'GET');
$req->setLaravelSession($app['session']->driver());
try {
    $res = $httpKernel->handle($req);
    $status = $res->getStatusCode();
    echo ($status === 200) ? "✅ PASS: Admin Arsip Surat List (HTTP 200)\n" : "❌ FAIL: Admin Arsip Surat List (HTTP {$status})\n";
} catch (\Throwable $e) {
    echo "❌ CRASH: Admin Arsip Surat List - " . $e->getMessage() . "\n";
}

// 7. Admin Tracking Surat List
$req = Request::create('/admin/surat/tracking', 'GET');
$req->setLaravelSession($app['session']->driver());
try {
    $res = $httpKernel->handle($req);
    $status = $res->getStatusCode();
    echo ($status === 200) ? "✅ PASS: Admin Tracking Surat List (HTTP 200)\n" : "❌ FAIL: Admin Tracking Surat List (HTTP {$status})\n";
} catch (\Throwable $e) {
    echo "❌ CRASH: Admin Tracking Surat List - " . $e->getMessage() . "\n";
}

// 8. Test Surat Full Lifecycle (Submit -> Verify -> Approve -> PDF)
echo "\n--- TESTING FULL SURAT LIFECYCLE (VERIFY, APPROVE & PDF GENERATION) ---\n";
if ($jenisSurat && $wargaUser) {
    try {
        $suratController = new SuratController();

        // Create new PengajuanSurat instance
        $pengajuan = PengajuanSurat::create([
            'user_id' => $wargaUser->id,
            'jenis_surat_id' => $jenisSurat->id,
            'kode_tracking' => 'TRK-' . date('Ymd') . '-' . rand(1000, 9999),
            'keterangan' => 'Keperluan pengujian otomasi surat',
            'status' => 'diajukan',
            'butuh_ttd_fisik' => true,
            'tanggal_diajukan' => now(),
        ]);
        echo "1. Created dummy Pengajuan Surat ID #{$pengajuan->id} [{$pengajuan->kode_tracking}]\n";

        // Admin Verify
        Auth::guard('web')->login($superAdmin);
        $res = $suratController->verifikasi($pengajuan);
        $pengajuan->refresh();
        echo "2. Admin Verify Step -> Status: {$pengajuan->status}\n";

        // Kades Approve
        Auth::guard('web')->login($kades);
        $res = $suratController->approve(new Request(), $pengajuan);
        $pengajuan->refresh();
        echo "3. Kades Approve Step -> Status: {$pengajuan->status}, Nomor Surat: {$pengajuan->nomor_surat}\n";

        // Test PDF generation
        $pdfRes = $suratController->pdf($pengajuan);
        $status = $pdfRes->getStatusCode();
        $headers = $pdfRes->headers->get('content-type', '');
        echo "4. Generate PDF Step -> HTTP {$status}, Content-Type: {$headers}\n";
        
        if ($status === 200 && str_contains($headers, 'pdf')) {
            echo "✅ PASS: PDF Generation Successful!\n";
        } else {
            echo "❌ FAIL: PDF Generation Issue (HTTP {$status})\n";
        }

        // Cleanup test data
        $pengajuan->delete();
        echo "5. Cleaned up dummy test pengajuan surat.\n";
    } catch (\Throwable $e) {
        echo "❌ CRASH in Surat Lifecycle: " . $e->getMessage() . " on line " . $e->getLine() . "\n";
    }
}
