<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengaduan;
use App\Http\Controllers\Admin\PengaduanController;
use Illuminate\Http\Request;

echo "1. Testing Pengaduan search query...\n";
try {
    $req = new Request(['search' => 'jalan']);
    $controller = new PengaduanController();
    $res = $controller->index($req);
    echo "Success! Pengaduan search executed without SQL errors.\n";
} catch (Exception $e) {
    echo "Error in Pengaduan search: " . $e->getMessage() . "\n";
}

echo "2. Testing Global Search query...\n";
try {
    $searchController = new App\Http\Controllers\Admin\SearchController();
    $req = new Request(['q' => 'jalan']);
    $res = $searchController->search($req);
    echo "Success! Global search executed without SQL errors.\n";
} catch (Exception $e) {
    echo "Error in Global search: " . $e->getMessage() . "\n";
}
