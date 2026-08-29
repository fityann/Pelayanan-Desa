<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$consoleKernel->bootstrap();

$httpKernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$adminUser = User::where('email', 'admin@puspamukti.local')->first();
$wargaUser = User::where('email', 'warga@puspamukti.local')->first();

$routesToTest = [
    ['url' => '/', 'guard' => null, 'user' => null],
    ['url' => '/rt/01', 'guard' => null, 'user' => null],
    ['url' => '/rt/01/login', 'guard' => null, 'user' => null],
    ['url' => '/informasi-desa', 'guard' => null, 'user' => null],
    ['url' => '/apbdes-publik', 'guard' => null, 'user' => null],
    ['url' => '/admin-gate', 'guard' => null, 'user' => null],
    ['url' => '/pengaduan/buat', 'guard' => 'warga', 'user' => $wargaUser],
    ['url' => '/rt/01/surat', 'guard' => 'warga', 'user' => $wargaUser],
    ['url' => '/rt/01/chat', 'guard' => 'warga', 'user' => $wargaUser],
    ['url' => '/rt/01/profil', 'guard' => 'warga', 'user' => $wargaUser],
    ['url' => '/dashboard', 'guard' => 'web', 'user' => $adminUser],
    ['url' => '/admin/pengaduan', 'guard' => 'web', 'user' => $adminUser],
    ['url' => '/admin/users', 'guard' => 'web', 'user' => $adminUser],
    ['url' => '/admin/keluarga', 'guard' => 'web', 'user' => $adminUser],
    ['url' => '/admin/penduduk', 'guard' => 'web', 'user' => $adminUser],
    ['url' => '/admin/apbdes', 'guard' => 'web', 'user' => $adminUser],
    ['url' => '/admin/informasi', 'guard' => 'web', 'user' => $adminUser],
];

echo "=== DOM & ROUTE TESTING RESULTS ===\n\n";

$results = [];
foreach ($routesToTest as $item) {
    $url = $item['url'];
    $guard = $item['guard'];
    $user = $item['user'];

    Auth::logout();
    if ($guard && $user) {
        Auth::guard($guard)->login($user);
        if ($guard === 'web') {
            Auth::guard('warga')->login($user);
        }
    }

    // Set session for admin gate if testing admin login
    $sessionData = [];
    if ($url === '/admin-login') {
        $sessionData['admin_gate_passed'] = true;
    }
    if ($guard === 'warga') {
        $sessionData['warga_rt'] = '01';
        $sessionData['warga_rw'] = '01';
    }

    $request = Request::create($url, 'GET');
    $request->setLaravelSession($app['session']->driver());
    foreach ($sessionData as $k => $v) {
        $request->session()->put($k, $v);
    }

    try {
        $response = $httpKernel->handle($request);
        $status = $response->getStatusCode();
        $content = $response->getContent();
        
        $errorFound = false;
        $errorMsg = '';
        if ($status >= 400) {
            $errorFound = true;
            $errorMsg = "HTTP Status Code {$status}";
        } elseif (str_contains($content, 'Exception') || str_contains($content, 'Stack trace') || str_contains($content, 'SQLSTATE')) {
            $errorFound = true;
            $errorMsg = "Contains Exception/Trace/SQL Error";
        }

        if ($errorFound) {
            echo "❌ FAIL: [{$url}] - {$errorMsg}\n";
            $results[] = ['url' => $url, 'status' => 'FAIL', 'msg' => $errorMsg];
        } else {
            echo "✅ PASS: [{$url}] - HTTP {$status}\n";
            $results[] = ['url' => $url, 'status' => 'PASS', 'msg' => "HTTP {$status}"];
        }
    } catch (\Throwable $e) {
        echo "❌ CRASH: [{$url}] - " . $e->getMessage() . "\n";
        $results[] = ['url' => $url, 'status' => 'CRASH', 'msg' => $e->getMessage()];
    }
}

echo "\nSummary: Tested " . count($routesToTest) . " routes.\n";
