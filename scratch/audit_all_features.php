<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== START SILAPU FULL SYSTEM AUDIT ===\n\n";

$issues = [];

// 1. Audit Database Seeders / Models
$models = [
    'Penduduk' => \App\Models\Penduduk::class,
    'Keluarga' => \App\Models\Keluarga::class,
    'PengajuanSurat' => \App\Models\PengajuanSurat::class,
    'JenisSurat' => \App\Models\JenisSurat::class,
    'Pengaduan' => \App\Models\Pengaduan::class,
    'Informasi' => \App\Models\Informasi::class,
    'Apbde' => \App\Models\Apbde::class,
    'Musrenbang' => \App\Models\Musrenbang::class,
    'Notification' => \App\Models\Notification::class,
    'Chat' => \App\Models\Chat::class,
    'RtQrCode' => \App\Models\RtQrCode::class,
];

foreach ($models as $name => $class) {
    try {
        $count = $class::count();
        echo "[OK] Model $name: $count records found.\n";
    } catch (\Exception $e) {
        $issues[] = "Model $name error: " . $e->getMessage();
        echo "[ERROR] Model $name: " . $e->getMessage() . "\n";
    }
}

echo "\n--- AUDITING VIEWS ---\n";
// 2. Audit Views compilation
$viewDir = resource_path('views');
$viewFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));
$viewErrors = 0;

foreach ($viewFiles as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $relativePath = str_replace($viewDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
        $viewName = str_replace(['/', '\\', '.blade.php'], ['.', '.', ''], $relativePath);
        try {
            // Render Blade to check syntax
            app('view')->make($viewName, [
                'rt' => '01',
                'rw' => '01',
                'isRtScoped' => true,
                'currentRoute' => 'warga.rt.landing',
                'greeting' => 'Selamat Pagi',
                'stats' => ['total_scans' => 10],
                'pengaduanStats' => ['total_pengaduan' => 5, 'pengaduan_selesai' => 4],
                'beritaTerbaru' => collect([]),
                'agendaTerdekat' => collect([]),
                'penduduks' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                'keluargas' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                'pengajuans' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                'pengaduans' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                'informasis' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                'apbdesList' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                'users' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                'roles' => collect([]),
                'permissions' => collect([]),
                'notifications' => collect([]),
                'chats' => collect([]),
                'surat' => new \App\Models\PengajuanSurat(),
                'jenisSuratList' => collect([]),
            ])->render();
        } catch (\Exception $e) {
            // Ignore missing variable notices for optional view testing, but log syntax or undefined directive errors
            if (!str_contains($e->getMessage(), 'Undefined variable')) {
                $issues[] = "View [$relativePath]: " . $e->getMessage();
                echo "[VIEW ISSUE] $relativePath: " . $e->getMessage() . "\n";
                $viewErrors++;
            }
        }
    }
}

if ($viewErrors === 0) {
    echo "[OK] All Blade views compiled cleanly without directive/syntax errors.\n";
}

echo "\n--- AUDITING ROUTES ---\n";
$routes = Route::getRoutes();
$totalRoutes = count($routes);
echo "[OK] Total registered routes: $totalRoutes\n";

echo "\n=== AUDIT SUMMARY ===\n";
if (empty($issues)) {
    echo "NO CRITICAL ERRORS FOUND!\n";
} else {
    echo "FOUND " . count($issues) . " ISSUES TO FIX:\n";
    foreach ($issues as $idx => $iss) {
        echo ($idx + 1) . ". $iss\n";
    }
}
