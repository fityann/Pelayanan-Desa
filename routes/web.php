<?php

use App\Http\Controllers\Admin\ApbdesController;
use App\Http\Controllers\Admin\InformasiController;
use App\Http\Controllers\Admin\KeluargaController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cek-nik/{nik}', function (string $nik) {
    if (!preg_match('/^\d{16}$/', $nik)) {
        return response()->json(['found' => false]);
    }

    $penduduk = Penduduk::where('nik', $nik)->first();
    if (!$penduduk) {
        return response()->json(['found' => false]);
    }

    // Hanya field publik/minimal yang dibutuhkan form register (autofill)
    return response()->json([
        'found' => true,
        'data' => [
            'nama' => $penduduk->nama,
            'alamat' => $penduduk->alamat,
            'rt' => $penduduk->rt,
            'rw' => $penduduk->rw,
        ],
    ]);
})->name('cek-nik');

Route::get('/informasi-desa', [InformasiController::class, 'publik'])->name('informasi.publik');
Route::get('/apbdes-publik', [ApbdesController::class, 'publik'])->name('apbdes.publik');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==== Area Admin/Perangkat Desa (dilindungi role) ====
Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'verified', 'role:Super Admin|Kepala Desa|Sekretaris Desa|Bendahara|Admin Desa'])
    ->group(function () {
        Route::resource('users', UserController::class)->middleware('can_manage_users');
        Route::resource('keluarga', KeluargaController::class);
        Route::resource('penduduk', PendudukController::class);
        Route::get('penduduk-import', [PendudukController::class, 'import'])->name('penduduk.import');
        Route::post('penduduk-import', [PendudukController::class, 'importStore'])->name('penduduk.import.store');

        // Surat
        Route::get('surat/jenis', [SuratController::class, 'jenisSurat'])->name('surat.jenis');
        Route::post('surat/jenis', [SuratController::class, 'storeJenisSurat'])->name('surat.jenis.store');
        Route::get('surat/pengajuan', [SuratController::class, 'pengajuanMasuk'])->name('surat.pengajuan');
        Route::post('surat/{pengajuanSurat}/verifikasi', [SuratController::class, 'verifikasi'])->name('surat.verifikasi');
        Route::post('surat/{pengajuanSurat}/approve', [SuratController::class, 'approve'])->name('surat.approve');
        Route::post('surat/{pengajuanSurat}/reject', [SuratController::class, 'reject'])->name('surat.reject');
        Route::post('surat/{pengajuanSurat}/siap-ambil', [SuratController::class, 'siapAmbil'])->name('surat.siap-ambil');
        Route::post('surat/{pengajuanSurat}/selesai', [SuratController::class, 'selesai'])->name('surat.selesai');
        Route::get('surat/arsip', [SuratController::class, 'arsip'])->name('surat.arsip');
        Route::get('surat/tracking', [SuratController::class, 'tracking'])->name('surat.tracking');

        // APBDes
        Route::get('apbdes', [ApbdesController::class, 'index'])->name('apbdes.index');
        Route::get('apbdes/create', [ApbdesController::class, 'create'])->name('apbdes.create');
        Route::post('apbdes', [ApbdesController::class, 'store'])->name('apbdes.store');
        Route::post('apbdes/{apbde}/review', [ApbdesController::class, 'review'])->name('apbdes.review');
        Route::post('apbdes/{apbde}/publish', [ApbdesController::class, 'publish'])->name('apbdes.publish');
        Route::delete('apbdes/{apbde}', [ApbdesController::class, 'destroy'])->name('apbdes.destroy');

        // Pengaduan
        Route::get('pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::post('pengaduan/{pengaduan}/proses', [PengaduanController::class, 'proses'])->name('pengaduan.proses');
        Route::post('pengaduan/{pengaduan}/selesai', [PengaduanController::class, 'selesai'])->name('pengaduan.selesai');
        Route::delete('pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');

        // Informasi
        Route::get('informasi', [InformasiController::class, 'index'])->name('informasi.index');
        Route::get('informasi/create', [InformasiController::class, 'create'])->name('informasi.create');
        Route::post('informasi', [InformasiController::class, 'store'])->name('informasi.store');
        Route::get('informasi/{informasi}/edit', [InformasiController::class, 'edit'])->name('informasi.edit');
        Route::patch('informasi/{informasi}', [InformasiController::class, 'update'])->name('informasi.update');
        Route::post('informasi/{informasi}/publish', [InformasiController::class, 'publish'])->name('informasi.publish');
        Route::delete('informasi/{informasi}', [InformasiController::class, 'destroy'])->name('informasi.destroy');

        // Role & Permission
        Route::get('roles', [RoleController::class, 'index'])->middleware('can_manage_users')->name('roles.index');
        Route::post('roles/update', [RoleController::class, 'update'])->middleware('can_manage_users')->name('roles.update');
        Route::post('roles/sync', [RoleController::class, 'syncAll'])->middleware('can_manage_users')->name('roles.sync');
    });

require __DIR__.'/auth.php';
