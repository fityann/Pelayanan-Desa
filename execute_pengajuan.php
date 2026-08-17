<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$controller = $app->make(\App\Http\Controllers\Admin\SuratController::class);
$request = Illuminate\Http\Request::create('/', 'POST');
$request->setUserResolver(function () {
    return \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'Kepala Desa'); })->first();
});

// Start processing the first 'diajukan' pengajuan
$pengajuan = \App\Models\PengajuanSurat::where('status', 'diajukan')->first();
if (!$pengajuan) {
    echo "Tidak ada pengajuan yang berstatus 'diajukan'.\n";
    exit;
}

echo "Memverifikasi pengajuan ID: {$pengajuan->id}...\n";
$controller->verifikasi($pengajuan);
$pengajuan->refresh();
echo "Status setelah verifikasi: {$pengajuan->status}\n";

if ($pengajuan->status === 'diverifikasi_admin') {
    echo "Menyetujui pengajuan ID: {$pengajuan->id}...\n";
    $controller->approve($request, $pengajuan);
    $pengajuan->refresh();
    echo "Status setelah disetujui: {$pengajuan->status}\n";
}

if ($pengajuan->status === 'menunggu_ttd_fisik') {
    echo "Menyelesaikan pengajuan ID: {$pengajuan->id}...\n";
    $controller->selesai($pengajuan);
    $pengajuan->refresh();
    echo "Status setelah selesai: {$pengajuan->status}\n";
}

echo "Selesai.\n";
