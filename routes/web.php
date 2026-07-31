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
use App\Http\Controllers\Warga\PengaduanController as WargaPengaduanController;
use App\Http\Controllers\Warga\SuratController as WargaSuratController;
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

// ==== Layanan Warga (Fase 1) ====
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('layanan/surat')->name('warga.surat.')->group(function () {
        Route::get('/', [WargaSuratController::class, 'index'])->name('index');
        Route::get('/{jenisSurat}/buat', [WargaSuratController::class, 'create'])->name('create');
        Route::post('/{jenisSurat}', [WargaSuratController::class, 'store'])->name('store');
        Route::get('/saya/riwayat', [WargaSuratController::class, 'riwayat'])->name('riwayat');
        Route::get('/{pengajuan}/status', [WargaSuratController::class, 'status'])->name('status');
        Route::get('/{pengajuan}/pdf', [WargaSuratController::class, 'pdf'])->name('pdf');
    });

    // QR Code pengaduan (PRD 1.5): /pengaduan/buat
    Route::get('/pengaduan/buat', [WargaPengaduanController::class, 'create'])->name('pengaduan.buat');
    Route::post('/pengaduan', [WargaPengaduanController::class, 'store'])->name('pengaduan.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==== Area Admin/Perangkat Desa (dilindungi role + permission CRUD) ====
Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'verified', 'role:Super Admin|Kepala Desa|Sekretaris Desa|Bendahara|Admin Desa'])
    ->group(function () {
        // Manajemen User
        Route::get('users', [UserController::class, 'index'])->middleware(['permission:R Manajemen User', 'can_manage_users'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->middleware(['permission:C Manajemen User', 'can_manage_users'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->middleware(['permission:C Manajemen User', 'can_manage_users'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware(['permission:U Manajemen User', 'can_manage_users'])->name('users.edit');
        Route::match(['put', 'patch'], 'users/{user}', [UserController::class, 'update'])->middleware(['permission:U Manajemen User', 'can_manage_users'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware(['permission:D Manajemen User', 'can_manage_users'])->name('users.destroy');

        // Keluarga
        Route::get('keluarga', [KeluargaController::class, 'index'])->middleware('permission:R Keluarga')->name('keluarga.index');
        Route::get('keluarga/create', [KeluargaController::class, 'create'])->middleware('permission:C Keluarga')->name('keluarga.create');
        Route::post('keluarga', [KeluargaController::class, 'store'])->middleware('permission:C Keluarga')->name('keluarga.store');
        Route::get('keluarga/{keluarga}/edit', [KeluargaController::class, 'edit'])->middleware('permission:U Keluarga')->name('keluarga.edit');
        Route::match(['put', 'patch'], 'keluarga/{keluarga}', [KeluargaController::class, 'update'])->middleware('permission:U Keluarga')->name('keluarga.update');
        Route::delete('keluarga/{keluarga}', [KeluargaController::class, 'destroy'])->middleware('permission:D Keluarga')->name('keluarga.destroy');

        // Penduduk
        Route::get('penduduk', [PendudukController::class, 'index'])->middleware('permission:R Penduduk')->name('penduduk.index');
        Route::get('penduduk/create', [PendudukController::class, 'create'])->middleware('permission:C Penduduk')->name('penduduk.create');
        Route::post('penduduk', [PendudukController::class, 'store'])->middleware('permission:C Penduduk')->name('penduduk.store');
        Route::get('penduduk/{penduduk}/edit', [PendudukController::class, 'edit'])->middleware('permission:U Penduduk')->name('penduduk.edit');
        Route::match(['put', 'patch'], 'penduduk/{penduduk}', [PendudukController::class, 'update'])->middleware('permission:U Penduduk')->name('penduduk.update');
        Route::delete('penduduk/{penduduk}', [PendudukController::class, 'destroy'])->middleware('permission:D Penduduk')->name('penduduk.destroy');
        Route::get('penduduk-import', [PendudukController::class, 'import'])->middleware('permission:C Penduduk')->name('penduduk.import');
        Route::post('penduduk-import', [PendudukController::class, 'importStore'])->middleware('permission:C Penduduk')->name('penduduk.import.store');

        // Surat (jenis surat)
        Route::get('surat/jenis', [SuratController::class, 'jenisSurat'])->middleware('permission:R Surat')->name('surat.jenis');
        Route::post('surat/jenis', [SuratController::class, 'storeJenisSurat'])->middleware('permission:C Surat')->name('surat.jenis.store');

        // Pengajuan Surat (pemrosesan)
        Route::get('surat/pengajuan', [SuratController::class, 'pengajuanMasuk'])->middleware('permission:R Pengajuan Surat')->name('surat.pengajuan');
        Route::post('surat/{pengajuan}/verifikasi', [SuratController::class, 'verifikasi'])->middleware('permission:U Pengajuan Surat')->name('surat.verifikasi');
        Route::post('surat/{pengajuan}/approve', [SuratController::class, 'approve'])->middleware('permission:U Pengajuan Surat')->name('surat.approve');
        Route::post('surat/{pengajuan}/reject', [SuratController::class, 'reject'])->middleware('permission:U Pengajuan Surat')->name('surat.reject');
        Route::post('surat/{pengajuan}/selesai', [SuratController::class, 'selesai'])->middleware('permission:U Pengajuan Surat')->name('surat.selesai');
        Route::get('surat/{pengajuan}/pdf', [SuratController::class, 'pdf'])->middleware('permission:R Pengajuan Surat')->name('surat.pdf');

        // Arsip Surat
        Route::get('surat/arsip', [SuratController::class, 'arsip'])->middleware('permission:R Arsip Surat')->name('surat.arsip');
        Route::get('surat/tracking', [SuratController::class, 'tracking'])->middleware('permission:R Pengajuan Surat')->name('surat.tracking');

        // APBDes
        Route::get('apbdes', [ApbdesController::class, 'index'])->middleware('permission:R APBDes')->name('apbdes.index');
        Route::get('apbdes/create', [ApbdesController::class, 'create'])->middleware('permission:C APBDes')->name('apbdes.create');
        Route::post('apbdes', [ApbdesController::class, 'store'])->middleware('permission:C APBDes')->name('apbdes.store');
        Route::post('apbdes/{apbde}/review', [ApbdesController::class, 'review'])->middleware('permission:U APBDes')->name('apbdes.review');
        Route::post('apbdes/{apbde}/publish', [ApbdesController::class, 'publish'])->middleware('permission:U APBDes')->name('apbdes.publish');
        Route::delete('apbdes/{apbde}', [ApbdesController::class, 'destroy'])->middleware('permission:D APBDes')->name('apbdes.destroy');

        // Pengaduan
        Route::get('pengaduan', [PengaduanController::class, 'index'])->middleware('permission:R Pengaduan')->name('pengaduan.index');
        Route::get('pengaduan/create', [PengaduanController::class, 'create'])->middleware('permission:C Pengaduan')->name('pengaduan.create');
        Route::post('pengaduan', [PengaduanController::class, 'store'])->middleware('permission:C Pengaduan')->name('pengaduan.store');
        Route::post('pengaduan/{pengaduan}/proses', [PengaduanController::class, 'proses'])->middleware('permission:U Pengaduan')->name('pengaduan.proses');
        Route::post('pengaduan/{pengaduan}/selesai', [PengaduanController::class, 'selesai'])->middleware('permission:U Pengaduan')->name('pengaduan.selesai');
        Route::delete('pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])->middleware('permission:D Pengaduan')->name('pengaduan.destroy');

        // Informasi
        Route::get('informasi', [InformasiController::class, 'index'])->middleware('permission:R Informasi')->name('informasi.index');
        Route::get('informasi/create', [InformasiController::class, 'create'])->middleware('permission:C Informasi')->name('informasi.create');
        Route::post('informasi', [InformasiController::class, 'store'])->middleware('permission:C Informasi')->name('informasi.store');
        Route::get('informasi/{informasi}/edit', [InformasiController::class, 'edit'])->middleware('permission:U Informasi')->name('informasi.edit');
        Route::match(['put', 'patch'], 'informasi/{informasi}', [InformasiController::class, 'update'])->middleware('permission:U Informasi')->name('informasi.update');
        Route::post('informasi/{informasi}/publish', [InformasiController::class, 'publish'])->middleware('permission:U Informasi')->name('informasi.publish');
        Route::delete('informasi/{informasi}', [InformasiController::class, 'destroy'])->middleware('permission:D Informasi')->name('informasi.destroy');

        // Role & Permission
        Route::get('roles', [RoleController::class, 'index'])->middleware(['permission:R Role & Permission', 'can_manage_users'])->name('roles.index');
        Route::post('roles/update', [RoleController::class, 'update'])->middleware(['permission:U Role & Permission', 'can_manage_users'])->name('roles.update');
        Route::post('roles/sync', [RoleController::class, 'syncAll'])->middleware(['permission:U Role & Permission', 'can_manage_users'])->name('roles.sync');
    });

require __DIR__.'/auth.php';
