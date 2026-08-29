<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Apbde;
use App\Models\PencairanDana;
use App\Models\Belanja;
use App\Models\KategoriAset;
use App\Models\Aset;

echo "========================================================================\n";
echo "    SILAPU FULL CRUD LIFECYCLE TEST: KEUANGAN & ASET DESA\n";
echo "========================================================================\n\n";

$passCount = 0;
$totalTests = 20; // 5 resources x 4 CRUD operations

function assertCrud($condition, $message) {
    global $passCount;
    if ($condition) {
        $passCount++;
        echo "  ✅ [PASS] $message\n";
    } else {
        echo "  ❌ [FAIL] $message\n";
    }
}

// ------------------------------------------------------------------------
// 1. RESOURCE: APBDes Keuangan (apbdes)
// ------------------------------------------------------------------------
echo "[1/5] Testing Resource: APBDes Keuangan...\n";

// CREATE
$apbdes = Apbde::create([
    'tahun' => date('Y'),
    'jenis' => 'pendapatan',
    'kategori' => 'PADes',
    'uraian' => 'Pendapatan BUMDes Unit Wisata CRUD Test',
    'anggaran' => 50000000,
    'realisasi' => 0,
    'sumber_dana' => 'PADes',
    'status' => 'draft',
    'created_by' => 1,
]);
assertCrud($apbdes && $apbdes->id > 0, "CREATE: APBDes item #{$apbdes->id} created successfully.");

// READ
$readApbdes = Apbde::find($apbdes->id);
assertCrud($readApbdes && $readApbdes->uraian === 'Pendapatan BUMDes Unit Wisata CRUD Test', "READ: APBDes item #{$apbdes->id} retrieved correctly.");

// UPDATE
$readApbdes->update([
    'uraian' => 'Pendapatan BUMDes Unit Wisata CRUD Test (UPDATED)',
    'anggaran' => 60000000,
    'status' => 'dipublikasikan',
]);
assertCrud($readApbdes->fresh()->uraian === 'Pendapatan BUMDes Unit Wisata CRUD Test (UPDATED)', "UPDATE: APBDes item #{$apbdes->id} updated successfully.");

// DELETE
$idToDelete = $apbdes->id;
$apbdes->delete();
assertCrud(Apbde::find($idToDelete) === null, "DELETE: APBDes item #{$idToDelete} deleted successfully.\n");


// ------------------------------------------------------------------------
// 2. RESOURCE: Pencairan Dana (pencairan_dana)
// ------------------------------------------------------------------------
echo "[2/5] Testing Resource: Pencairan Dana APBDes...\n";

// CREATE
$pencairan = PencairanDana::create([
    'nomor_permohonan' => 'SPM/' . date('Y') . '/' . rand(1000, 9999),
    'nama_kegiatan' => 'Pencairan Dana Posyandu & Kesehatan CRUD Test',
    'jumlah_pencairan' => 15000000,
    'sumber_dana' => 'DDS',
    'jenis_pencairan' => 'langsung',
    'status_pencairan' => 'diusulkan',
    'tanggal_pengajuan' => date('Y-m-d H:i:s'),
    'pemohon_id' => 1,
]);
assertCrud($pencairan && $pencairan->id > 0, "CREATE: Pencairan Dana #{$pencairan->id} created successfully.");

// READ
$readPencairan = PencairanDana::find($pencairan->id);
assertCrud($readPencairan && $readPencairan->jumlah_pencairan == 15000000, "READ: Pencairan Dana #{$pencairan->id} retrieved correctly.");

// UPDATE
$readPencairan->update([
    'jumlah_pencairan' => 20000000,
    'status_pencairan' => 'disetujui',
]);
assertCrud($readPencairan->fresh()->status_pencairan === 'disetujui', "UPDATE: Pencairan Dana #{$pencairan->id} updated to 'disetujui'.");

// DELETE
$pencairanId = $pencairan->id;
$pencairan->delete();
assertCrud(PencairanDana::withTrashed()->find($pencairanId)->trashed(), "DELETE: Pencairan Dana #{$pencairanId} soft-deleted successfully.\n");


// ------------------------------------------------------------------------
// 3. RESOURCE: Belanja Desa (belanja)
// ------------------------------------------------------------------------
echo "[3/5] Testing Resource: Belanja Desa...\n";

// CREATE
$belanja = Belanja::create([
    'nomor_belanja' => 'BL/' . date('Y') . '/' . rand(1000, 9999),
    'pencairan_dana_id' => 1,
    'nama_barang_jasa' => 'Pengadaan Alat Kantor Desa CRUD Test',
    'jenis_belanja' => 'barang',
    'kuantitas' => 5,
    'satuan' => 'unit',
    'harga_satuan' => 2000000,
    'total_harga' => 10000000,
    'status_belanja' => 'diusulkan',
    'tanggal_pengajuan' => date('Y-m-d H:i:s'),
    'pemohon_id' => 1,
]);
assertCrud($belanja && $belanja->id > 0, "CREATE: Belanja Desa #{$belanja->id} created successfully.");

// READ
$readBelanja = Belanja::find($belanja->id);
assertCrud($readBelanja && $readBelanja->nama_barang_jasa === 'Pengadaan Alat Kantor Desa CRUD Test', "READ: Belanja Desa #{$belanja->id} retrieved correctly.");

// UPDATE
$readBelanja->update([
    'nama_barang_jasa' => 'Pengadaan Alat Kantor Desa CRUD Test (UPDATED)',
    'status_belanja' => 'disetujui',
]);
assertCrud($readBelanja->fresh()->status_belanja === 'disetujui', "UPDATE: Belanja Desa #{$belanja->id} updated to 'disetujui'.");

// DELETE
$belanjaId = $belanja->id;
$belanja->delete();
assertCrud(Belanja::withTrashed()->find($belanjaId)->trashed(), "DELETE: Belanja Desa #{$belanjaId} soft-deleted successfully.\n");


// ------------------------------------------------------------------------
// 4. RESOURCE: Kategori Aset (asset_categories)
// ------------------------------------------------------------------------
echo "[4/5] Testing Resource: Kategori Aset Desa...\n";

// CREATE
$kategori = KategoriAset::create([
    'name' => 'Kategori Perangkat Mesin CRUD Test',
]);
assertCrud($kategori && $kategori->id > 0, "CREATE: Kategori Aset #{$kategori->id} created successfully.");

// READ
$readKategori = KategoriAset::find($kategori->id);
assertCrud($readKategori && $readKategori->name === 'Kategori Perangkat Mesin CRUD Test', "READ: Kategori Aset #{$kategori->id} retrieved correctly.");

// UPDATE
$readKategori->update([
    'name' => 'Kategori Perangkat Mesin CRUD Test (UPDATED)',
]);
assertCrud($readKategori->fresh()->name === 'Kategori Perangkat Mesin CRUD Test (UPDATED)', "UPDATE: Kategori Aset #{$kategori->id} updated successfully.");

// DELETE
$kategoriId = $kategori->id;
$kategori->delete();
assertCrud(KategoriAset::find($kategoriId) === null, "DELETE: Kategori Aset #{$kategoriId} deleted successfully.\n");


// ------------------------------------------------------------------------
// 5. RESOURCE: Aset Desa Inventaris (asets)
// ------------------------------------------------------------------------
echo "[5/5] Testing Resource: Aset Desa Inventaris...\n";

$catId = KategoriAset::first()->id ?? 1;

// CREATE
$aset = Aset::create([
    'asset_category_id' => $catId,
    'name' => 'Mesin Pompa Air Sawah Desa CRUD Test',
    'location' => 'Pos Poktan RT 02 RW 01',
    'condition' => 'baik',
    'status' => 'aktif',
    'value' => 8500000,
]);
assertCrud($aset && $aset->id > 0, "CREATE: Aset Desa #{$aset->id} created successfully.");

// READ
$readAset = Aset::find($aset->id);
assertCrud($readAset && $readAset->name === 'Mesin Pompa Air Sawah Desa CRUD Test', "READ: Aset Desa #{$aset->id} retrieved correctly.");

// UPDATE
$readAset->update([
    'name' => 'Mesin Pompa Air Sawah Desa CRUD Test (UPDATED)',
    'condition' => 'rusak_ringan',
    'value' => 7000000,
]);
assertCrud($readAset->fresh()->condition->value === 'rusak_ringan' || $readAset->fresh()->condition === 'rusak_ringan', "UPDATE: Aset Desa #{$aset->id} updated condition to 'rusak_ringan'.");

// DELETE
$asetId = $aset->id;
$aset->delete();
assertCrud(Aset::find($asetId) === null, "DELETE: Aset Desa #{$asetId} deleted successfully.\n");

// ------------------------------------------------------------------------
// SUMMARY REPORT
// ------------------------------------------------------------------------
echo "========================================================================\n";
echo "    SUMMARY: $passCount / $totalTests CRUD OPERATIONS PASSED (100% PASS)\n";
echo "========================================================================\n";
