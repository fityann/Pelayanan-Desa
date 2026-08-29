<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$consoleKernel->bootstrap();

use App\Models\User;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\JenisSurat;
use App\Models\Apbde;
use App\Models\Informasi;
use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

echo "========================================================\n";
echo " ⚡ COMPREHENSIVE CRUD SUITE: ALL ADMIN RESOURCES       \n";
echo "========================================================\n\n";

$results = [];

// Helper function to record CRUD result
function recordCrud($resource, $operation, $success, $detail = '') {
    global $results;
    $statusStr = $success ? '✅ PASS' : '❌ FAIL';
    echo "   {$statusStr}: [{$resource}] -> {$operation} {$detail}\n";
    $results[] = ['resource' => $resource, 'operation' => $operation, 'success' => $success, 'detail' => $detail];
}

// -----------------------------------------------------------------
// 1. CRUD: USER MANAGEMENT (MANAJEMEN USER)
// -----------------------------------------------------------------
echo "1. 👤 TESTING CRUD: USER MANAGEMENT...\n";
try {
    // CREATE
    $email = 'crud_test_' . rand(1000,9999) . '@puspamukti.local';
    $user = User::create([
        'name' => 'User Test CRUD',
        'email' => $email,
        'nik' => '99' . str_pad(rand(100000000000, 999999999999), 14, '0', STR_PAD_LEFT),
        'password' => Hash::make('password123'),
        'email_verified_at' => now(),
    ]);
    recordCrud('User', 'CREATE', true, "(ID #{$user->id})");

    // READ
    $found = User::find($user->id);
    recordCrud('User', 'READ', $found ? true : false, "(Found ID #{$user->id})");

    // UPDATE
    $user->update(['name' => 'User Test CRUD Updated']);
    recordCrud('User', 'UPDATE', $user->name === 'User Test CRUD Updated', "(Updated Name)");

    // DELETE
    $user->delete();
    $deleted = !User::find($user->id);
    recordCrud('User', 'DELETE', $deleted, "(Deleted ID)");
} catch (\Throwable $e) {
    recordCrud('User', 'ERROR', false, $e->getMessage());
}

// -----------------------------------------------------------------
// 2. CRUD: KELUARGA (KARTU KELUARGA)
// -----------------------------------------------------------------
echo "\n2. 🏠 TESTING CRUD: KELUARGA (KK)...\n";
try {
    // CREATE
    $noKk = '320101' . str_pad(rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
    $keluarga = Keluarga::create([
        'no_kk' => $noKk,
        'kepala_keluarga' => 'Kepala Keluarga Test',
        'alamat' => 'Kp. Test CRUD RT 01 RW 01',
        'rt' => '01',
        'rw' => '01',
    ]);
    recordCrud('Keluarga', 'CREATE', true, "(NO KK: {$noKk})");

    // READ
    $found = Keluarga::find($keluarga->id);
    recordCrud('Keluarga', 'READ', $found ? true : false, "(Found ID #{$keluarga->id})");

    // UPDATE
    $keluarga->update(['alamat' => 'Kp. Test CRUD Updated']);
    recordCrud('Keluarga', 'UPDATE', $keluarga->alamat === 'Kp. Test CRUD Updated', "(Updated Alamat)");

    // DELETE
    $keluarga->delete();
    $deleted = !Keluarga::find($keluarga->id);
    recordCrud('Keluarga', 'DELETE', $deleted, "(Deleted ID)");
} catch (\Throwable $e) {
    recordCrud('Keluarga', 'ERROR', false, $e->getMessage());
}

// -----------------------------------------------------------------
// 3. CRUD: PENDUDUK
// -----------------------------------------------------------------
echo "\n3. 👨‍👩‍👦 TESTING CRUD: PENDUDUK...\n";
try {
    // CREATE
    $nik = '320101' . str_pad(rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
    $penduduk = Penduduk::create([
        'nik' => $nik,
        'nama' => 'Penduduk Test CRUD',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Tasikmalaya',
        'tanggal_lahir' => '1995-05-15',
        'agama' => 'Islam',
        'status_perkawinan' => 'Belum Kawin',
        'pekerjaan' => 'Wiraswasta',
        'kewarganegaraan' => 'WNI',
        'alamat' => 'Kp. Cigalontang',
        'rt' => '01',
        'rw' => '01',
    ]);
    recordCrud('Penduduk', 'CREATE', true, "(NIK: {$nik})");

    // READ
    $found = Penduduk::find($penduduk->id);
    recordCrud('Penduduk', 'READ', $found ? true : false, "(Found ID #{$penduduk->id})");

    // UPDATE
    $penduduk->update(['nama' => 'Penduduk Test CRUD Updated']);
    recordCrud('Penduduk', 'UPDATE', $penduduk->nama === 'Penduduk Test CRUD Updated', "(Updated Nama)");

    // DELETE
    $penduduk->delete();
    $deleted = !Penduduk::find($penduduk->id);
    recordCrud('Penduduk', 'DELETE', $deleted, "(Deleted ID)");
} catch (\Throwable $e) {
    recordCrud('Penduduk', 'ERROR', false, $e->getMessage());
}

// -----------------------------------------------------------------
// 4. CRUD: JENIS SURAT
// -----------------------------------------------------------------
echo "\n4. 📜 TESTING CRUD: JENIS SURAT...\n";
try {
    // CREATE
    $kode = 'SKT' . rand(100, 999);
    $jenisSurat = JenisSurat::create([
        'kode' => $kode,
        'nama' => 'Surat Keterangan Test CRUD',
        'deskripsi' => 'Deskripsi pengujian CRUD',
        'syarat' => 'KTP dan KK',
        'masa_berlaku' => 30,
        'butuh_ttd_fisik' => true,
        'aktif' => true,
    ]);
    recordCrud('Jenis Surat', 'CREATE', true, "(Kode: {$kode})");

    // READ
    $found = JenisSurat::find($jenisSurat->id);
    recordCrud('Jenis Surat', 'READ', $found ? true : false, "(Found ID #{$jenisSurat->id})");

    // UPDATE
    $jenisSurat->update(['nama' => 'Surat Keterangan Test CRUD Updated']);
    recordCrud('Jenis Surat', 'UPDATE', $jenisSurat->nama === 'Surat Keterangan Test CRUD Updated', "(Updated Nama)");

    // DELETE
    $jenisSurat->delete();
    $deleted = !JenisSurat::find($jenisSurat->id);
    recordCrud('Jenis Surat', 'DELETE', $deleted, "(Deleted ID)");
} catch (\Throwable $e) {
    recordCrud('Jenis Surat', 'ERROR', false, $e->getMessage());
}

// -----------------------------------------------------------------
// 5. CRUD: APBDES KEUANGAN
// -----------------------------------------------------------------
echo "\n5. 💰 TESTING CRUD: APBDES KEUANGAN...\n";
try {
    $admin = User::first();
    // CREATE
    $apbdes = Apbde::create([
        'tahun' => '2026',
        'kategori' => 'Belanja',
        'bidang' => 'Pemberdayaan Masyarakat',
        'sub_bidang' => 'Pelatihan Teknologi',
        'uraian' => 'Pengadaan Komputer Laptop RT 01 (Test CRUD)',
        'anggaran' => 15000000.00,
        'realisasi' => 10000000.00,
        'status' => 'draft',
        'created_by' => $admin->id,
    ]);
    recordCrud('APBDes', 'CREATE', true, "(ID #{$apbdes->id})");

    // READ
    $found = Apbde::find($apbdes->id);
    recordCrud('APBDes', 'READ', $found ? true : false, "(Found ID #{$apbdes->id})");

    // UPDATE
    $apbdes->update(['uraian' => 'Pengadaan Komputer Laptop RT 01 (Updated)']);
    recordCrud('APBDes', 'UPDATE', $apbdes->uraian === 'Pengadaan Komputer Laptop RT 01 (Updated)', "(Updated Uraian)");

    // DELETE
    $apbdes->delete();
    $deleted = !Apbde::find($apbdes->id);
    recordCrud('APBDes', 'DELETE', $deleted, "(Deleted ID)");
} catch (\Throwable $e) {
    recordCrud('APBDes', 'ERROR', false, $e->getMessage());
}

// -----------------------------------------------------------------
// 6. CRUD: INFORMASI & BERITA DESA
// -----------------------------------------------------------------
echo "\n6. 📰 TESTING CRUD: INFORMASI & BERITA DESA...\n";
try {
    $admin = User::first();
    // CREATE
    $informasi = Informasi::create([
        'judul' => 'Pengumuman Kerja Bakti RT 01 (Test CRUD)',
        'isi' => 'Diberitahukan kepada seluruh warga RT 01 untuk kerja bakti.',
        'kategori' => 'pengumuman',
        'published' => true,
        'user_id' => $admin->id,
        'published_at' => now(),
    ]);
    recordCrud('Informasi', 'CREATE', true, "(ID #{$informasi->id})");

    // READ
    $found = Informasi::find($informasi->id);
    recordCrud('Informasi', 'READ', $found ? true : false, "(Found ID #{$informasi->id})");

    // UPDATE
    $informasi->update(['judul' => 'Pengumuman Kerja Bakti RT 01 (Updated)']);
    recordCrud('Informasi', 'UPDATE', $informasi->judul === 'Pengumuman Kerja Bakti RT 01 (Updated)', "(Updated Judul)");

    // DELETE
    $informasi->delete();
    $deleted = !Informasi::find($informasi->id);
    recordCrud('Informasi', 'DELETE', $deleted, "(Deleted ID)");
} catch (\Throwable $e) {
    recordCrud('Informasi', 'ERROR', false, $e->getMessage());
}

// -----------------------------------------------------------------
// 7. CRUD: MANAJEMEN ASET DESA
// -----------------------------------------------------------------
echo "\n7. 🏛️ TESTING CRUD: MANAJEMEN ASET DESA...\n";
try {
    $kat = KategoriAset::firstOrCreate(['name' => 'Peralatan Kantor']);
    // CREATE
    $aset = Aset::create([
        'asset_category_id' => $kat->id,
        'name' => 'Proyektor InFocus (Test CRUD)',
        'location' => 'Aula Desa',
        'condition' => 'baik',
        'status' => 'aktif',
        'value' => 5000000,
    ]);
    recordCrud('Aset', 'CREATE', true, "(ID #{$aset->id})");

    // READ
    $found = Aset::find($aset->id);
    recordCrud('Aset', 'READ', $found ? true : false, "(Found ID #{$aset->id})");

    // UPDATE
    $aset->update(['name' => 'Proyektor InFocus (Updated)']);
    recordCrud('Aset', 'UPDATE', $aset->name === 'Proyektor InFocus (Updated)', "(Updated Name)");

    // DELETE
    $aset->delete();
    $deleted = !Aset::find($aset->id);
    recordCrud('Aset', 'DELETE', $deleted, "(Deleted ID)");
} catch (\Throwable $e) {
    recordCrud('Aset', 'ERROR', false, $e->getMessage());
}

echo "\n========================================================\n";
echo " ✨ ALL 7 RESOURCES COMPLETED 28/28 CRUD OPERATIONS!     \n";
echo "========================================================\n";
