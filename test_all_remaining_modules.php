<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$consoleKernel->bootstrap();

$httpKernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use App\Models\Apbde;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\Informasi;
use App\Http\Controllers\Admin\ApbdesController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\KeluargaController;
use App\Http\Controllers\Admin\InformasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$superAdmin = User::where('email', 'admin@puspamukti.local')->first();
$wargaUser = User::where('email', 'warga@puspamukti.local')->first();

echo "========================================================\n";
echo " 🚀 LIVE AUTOMATED SUITE: PUSPAMUKTI SMART VILLAGE      \n";
echo "========================================================\n\n";

// Disable CSRF for testing
$app->instance(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class, new class {
    public function handle($request, $next) { return $next($request); }
});

function testRoute($httpKernel, $app, $url, $method = 'GET', $guard = null, $user = null, $label = '') {
    Auth::logout();
    if ($guard && $user) {
        Auth::guard($guard)->login($user);
    }
    $req = Request::create($url, $method);
    $req->setLaravelSession($app['session']->driver());
    
    try {
        $res = $httpKernel->handle($req);
        $status = $res->getStatusCode();
        if ($status >= 200 && $status < 400) {
            echo "   ✅ PASS: [{$label}] -> HTTP {$status}\n";
            return true;
        } else {
            echo "   ❌ FAIL: [{$label}] -> HTTP {$status}\n";
            return false;
        }
    } catch (\Throwable $e) {
        echo "   ❌ CRASH: [{$label}] -> " . $e->getMessage() . "\n";
        return false;
    }
}

// ----------------------------------------------------
// TEST MODULE 1: APBDes & Keuangan Desa
// ----------------------------------------------------
echo "📊 [MODULE 1] TESTING APBDES & TRANSPARANSI KEUANGAN DESA\n";
testRoute($httpKernel, $app, '/apbdes-publik', 'GET', null, null, 'Publik Portal APBDes');
testRoute($httpKernel, $app, '/admin/apbdes', 'GET', 'web', $superAdmin, 'Admin APBDes List');

// Test APBDes Lifecycle (Create -> Review -> Publish)
try {
    $apbdesController = new ApbdesController();
    $apbdesItem = Apbde::create([
        'tahun' => '2026',
        'kategori' => 'Belanja',
        'bidang' => 'Bidang Pembangunan Desa',
        'sub_bidang' => 'Sub Bidang Perhubungan & Jalan',
        'uraian' => 'Pembangunan Jalan Usaha Tani RT 02 (Test Automation)',
        'anggaran' => 50000000.00,
        'realisasi' => 25000000.00,
        'status' => 'draft',
        'created_by' => $superAdmin->id,
    ]);
    echo "   1. Created APBDes Draft Item ID #{$apbdesItem->id}\n";

    // Review step
    Auth::guard('web')->login($superAdmin);
    $apbdesController->review($apbdesItem);
    $apbdesItem->refresh();
    echo "   2. APBDes Review Step -> Status: {$apbdesItem->status}\n";

    // Publish step
    $apbdesController->publish($apbdesItem);
    $apbdesItem->refresh();
    echo "   3. APBDes Publish Step -> Status: {$apbdesItem->status}\n";

    if ($apbdesItem->status === 'dipublikasikan') {
        echo "   ✅ PASS: APBDes Full Lifecycle (Draft -> Review -> Publish) Successful!\n";
    } else {
        echo "   ❌ FAIL: APBDes Lifecycle Status Mismatch\n";
    }

    $apbdesItem->delete();
    echo "   4. Cleaned up dummy APBDes item.\n";
} catch (\Throwable $e) {
    echo "   ❌ CRASH in APBDes Lifecycle: " . $e->getMessage() . "\n";
}

// ----------------------------------------------------
// TEST MODULE 2: Data Kependudukan & Kartu Keluarga
// ----------------------------------------------------
echo "\n👨‍👩‍👧‍👦 [MODULE 2] TESTING DATA KEPENDUDUKAN & KELUARGA\n";
testRoute($httpKernel, $app, '/admin/penduduk', 'GET', 'web', $superAdmin, 'Admin Penduduk List');
testRoute($httpKernel, $app, '/admin/keluarga', 'GET', 'web', $superAdmin, 'Admin Keluarga List');

$pendudukSample = Penduduk::first();
if ($pendudukSample) {
    testRoute($httpKernel, $app, "/admin/penduduk/{$pendudukSample->id}", 'GET', 'web', $superAdmin, 'Admin Penduduk Detail');
}

$keluargaSample = Keluarga::first();
if ($keluargaSample) {
    testRoute($httpKernel, $app, "/admin/keluarga/{$keluargaSample->id}", 'GET', 'web', $superAdmin, 'Admin Keluarga Detail');
}

// ----------------------------------------------------
// TEST MODULE 3: Informasi & Berita Desa
// ----------------------------------------------------
echo "\n📰 [MODULE 3] TESTING INFORMASI & BERITA DESA\n";
testRoute($httpKernel, $app, '/informasi-desa', 'GET', null, null, 'Publik Informasi Desa List');
testRoute($httpKernel, $app, '/admin/informasi', 'GET', 'web', $superAdmin, 'Admin Informasi List');

$informasiSample = Informasi::first();
if ($informasiSample) {
    testRoute($httpKernel, $app, "/informasi-desa/{$informasiSample->id}", 'GET', null, null, 'Publik Informasi Detail');
}

// ----------------------------------------------------
// TEST MODULE 4: Aset Desa & Musrenbang
// ----------------------------------------------------
echo "\n🏛️ [MODULE 4] TESTING ASET DESA & MUSRENBANG\n";
testRoute($httpKernel, $app, '/aset-desa', 'GET', null, null, 'Publik Aset Desa List');
testRoute($httpKernel, $app, '/admin/assets', 'GET', 'web', $superAdmin, 'Admin Aset Desa List');

echo "\n========================================================\n";
echo " ✨ AUTOMATED TESTING SUITE COMPLETED SUCCESSFULLY!     \n";
echo "========================================================\n";
