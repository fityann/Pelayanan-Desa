<?php

use App\Http\Controllers\Admin\ApbdesController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\InformasiController;
use App\Http\Controllers\Admin\KeluargaController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Warga\ChatController as WargaChatController;
use App\Http\Controllers\Warga\NotificationController as WargaNotificationController;
use App\Http\Controllers\Warga\PengaduanController as WargaPengaduanController;
use App\Http\Controllers\Warga\SuratController as WargaSuratController;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('warga.rt.landing', ['rt' => '01']);
});

// ==== Portal Akses 2-Step Perangkat Desa / Admin ====
Route::middleware('guest')->group(function () {
    Route::get('/admin-gate', [\App\Http\Controllers\Auth\AdminGateController::class, 'showGate'])->name('admin.gate.show');
    Route::post('/admin-gate', [\App\Http\Controllers\Auth\AdminGateController::class, 'verifyGate'])->name('admin.gate.verify');
    Route::get('/admin-login', [\App\Http\Controllers\Auth\AdminGateController::class, 'showAdminLogin'])->name('admin.login.form');
    Route::post('/admin-login', [\App\Http\Controllers\Auth\AdminGateController::class, 'authenticateAdmin'])->name('admin.login.authenticate');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cek-nik/{nik}', function (string $nik) {
    if (!preg_match('/^\d{16}$/', $nik)) {
        return response()->json(['found' => false]);
    }

    $penduduk = Penduduk::where('nik', $nik)->first();
    if ($penduduk) {
        return response()->json([
            'found' => true,
            'data' => [
                'nama' => $penduduk->nama,
                'alamat' => $penduduk->alamat,
                'rt' => $penduduk->rt,
                'rw' => $penduduk->rw ?? '01',
            ],
        ]);
    }

    $user = \App\Models\User::where('nik', $nik)->first();
    if ($user) {
        return response()->json([
            'found' => true,
            'data' => [
                'nama' => $user->name,
                'alamat' => $user->address ?? '',
                'rt' => $user->rt ?? '01',
                'rw' => $user->rw ?? '01',
            ],
        ]);
    }

    return response()->json(['found' => false]);
})->name('cek-nik');

Route::get('/informasi-desa', [InformasiController::class, 'publik'])->name('informasi.publik');
Route::get('/apbdes-publik', [ApbdesController::class, 'publik'])->name('apbdes.publik');
Route::get('/aset-desa', [\App\Http\Controllers\AsetController::class, 'index'])->name('aset.publik');

// ==== QR Code untuk Warga per RT ====
Route::prefix('rt/{rt}')->name('warga.rt.')->group(function () {
    Route::get('/', [\App\Http\Controllers\WargaRtController::class, 'landing'])->name('landing');
    Route::get('/info', [\App\Http\Controllers\WargaRtController::class, 'infoDesa'])->name('info');
    Route::post('/pengaduan', [\App\Http\Controllers\WargaRtController::class, 'createPengaduan'])->name('createPengaduan');

    // Login warga (NIK + Nama) — khusus warga yang terdaftar di panel admin
    Route::get('/login', [\App\Http\Controllers\WargaRtController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\WargaRtController::class, 'authenticateWarga'])->name('login.authenticate');
    Route::post('/login-submit', [\App\Http\Controllers\WargaRtController::class, 'authenticateWarga'])->name('login.submit');
    Route::post('/logout', [\App\Http\Controllers\WargaRtController::class, 'logoutWarga'])->name('logout');

    // Layanan warga yang wajib login (NIK & Nama KTP)
    Route::middleware('warga.auth')->group(function () {
        Route::get('/profil', [\App\Http\Controllers\WargaProfileController::class, 'index'])->name('profil');
        Route::put('/profil', [\App\Http\Controllers\WargaProfileController::class, 'update'])->name('profil.update');

        Route::get('/surat', [WargaSuratController::class, 'indexRt'])->name('surat.index');
        Route::get('/surat/riwayat', [WargaSuratController::class, 'riwayatRt'])->name('surat.riwayat');
        Route::get('/surat/status/{kode}', [WargaSuratController::class, 'statusRt'])->name('surat.status');
        Route::get('/surat/pdf/{kode}', [WargaSuratController::class, 'pdf'])->name('surat.pdf');
        Route::get('/surat/{jenisSurat}/buat', [WargaSuratController::class, 'createRt'])->name('surat.create');
        Route::post('/surat/{jenisSurat}', [WargaSuratController::class, 'storeRt'])->name('surat.store');

        // Chat warga dengan admin desa (hanya warga yang sudah login)
        Route::get('/chat', [WargaChatController::class, 'index'])->name('chat');
        Route::get('/chat/data', [WargaChatController::class, 'data'])->name('chat.data');
        Route::post('/chat', [WargaChatController::class, 'kirim'])->name('chat.store');

        // Notifikasi warga (terlihat di icon lonceng)
        Route::get('/notif/data', [WargaNotificationController::class, 'data'])->name('notif.data');
        Route::post('/notif/{id}/read', [WargaNotificationController::class, 'markRead'])->name('notif.read');
        Route::post('/notif/read-all', [WargaNotificationController::class, 'markAll'])->name('notif.read-all');
    });
});

// Legacy route alias for /rt/{rt}/rw/{rw}
Route::prefix('rt/{rt}/rw/{rw}')->group(function () {
    Route::get('/', [\App\Http\Controllers\WargaRtController::class, 'landing']);
    Route::get('/info', [\App\Http\Controllers\WargaRtController::class, 'infoDesa']);
    Route::get('/login', [\App\Http\Controllers\WargaRtController::class, 'showLogin']);
    Route::post('/login', [\App\Http\Controllers\WargaRtController::class, 'authenticateWarga']);
    Route::post('/logout', [\App\Http\Controllers\WargaRtController::class, 'logoutWarga']);
});

// ==== Layanan Warga (Wajib Login Warga NIK & Nama) ====
Route::middleware('warga.auth')->group(function () {
    Route::prefix('layanan/surat')->name('warga.surat.')->group(function () {
        Route::get('/', [WargaSuratController::class, 'index'])->name('index');
        Route::get('/cek', [WargaSuratController::class, 'cek'])->name('cek');
        Route::get('/status/{kode}', [WargaSuratController::class, 'status'])->name('status');
        Route::get('/pdf/{kode}', [WargaSuratController::class, 'pdf'])->name('pdf');
        Route::get('/{jenisSurat}/buat', [WargaSuratController::class, 'create'])->name('create');
        Route::post('/{jenisSurat}', [WargaSuratController::class, 'store'])->name('store');
    });

    // Layanan Warga - Usulan Kegiatan (Musrenbang)
    Route::prefix('layanan/musrenbang')->name('warga.musrenbang.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Warga\MusrenbangController::class, 'index'])->name('index');
        Route::get('/{musrenbang}', [\App\Http\Controllers\Warga\MusrenbangController::class, 'show'])->name('show');
        Route::post('/{musrenbang}/support', [\App\Http\Controllers\Warga\MusrenbangController::class, 'support'])->name('support');
    });
});

Route::middleware('warga.auth')->group(function () {
    // QR Code pengaduan: /pengaduan/buat
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
        Route::get('penduduk-export', [PendudukController::class, 'export'])->middleware('permission:R Penduduk')->name('penduduk.export');

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
        Route::get('surat/arsip-export', [SuratController::class, 'exportArsip'])->middleware('permission:R Arsip Surat')->name('surat.arsip.export');
        Route::get('surat/tracking', [SuratController::class, 'tracking'])->middleware('permission:R Pengajuan Surat')->name('surat.tracking');

        // APBDes Dashboard (Fase 2)
        Route::get('apbdes/dashboard', [\App\Http\Controllers\ApbdesController::class, 'index'])->middleware('permission:R APBDes')->name('apbdes.dashboard');
        Route::get('apbdes/export', [\App\Http\Controllers\ApbdesController::class, 'export'])->middleware('permission:R APBDes')->name('apbdes.export');
        
        // APBDes Management
        Route::get('apbdes', [ApbdesController::class, 'index'])->middleware('permission:R APBDes')->name('apbdes.index');
        Route::get('apbdes/create', [ApbdesController::class, 'create'])->middleware('permission:C APBDes')->name('apbdes.create');
        Route::post('apbdes', [ApbdesController::class, 'store'])->middleware('permission:C APBDes')->name('apbdes.store');
        Route::get('apbdes/{apbde}/edit', [ApbdesController::class, 'edit'])->middleware('permission:U APBDes')->name('apbdes.edit');
        Route::match(['put', 'patch'], 'apbdes/{apbde}', [ApbdesController::class, 'update'])->middleware('permission:U APBDes')->name('apbdes.update');
        Route::post('apbdes/{apbde}/review', [ApbdesController::class, 'review'])->middleware('permission:U APBDes')->name('apbdes.review');
        Route::post('apbdes/{apbde}/publish', [ApbdesController::class, 'publish'])->middleware('permission:U APBDes')->name('apbdes.publish');
        Route::delete('apbdes/{apbde}', [ApbdesController::class, 'destroy'])->middleware('permission:D APBDes')->name('apbdes.destroy');
        
        // Manajemen Aset
        Route::resource('assets', \App\Http\Controllers\Admin\AsetController::class);
        Route::resource('kategori-aset', \App\Http\Controllers\Admin\KategoriAsetController::class)->except('show');
        
        // Musrenbang (Fase 2 - Perencanaan)
        Route::prefix('musrenbang')->name('musrenbang.')->group(function () {
            Route::get('/', [\App\Http\Controllers\MusrenbangController::class, 'index'])->middleware('permission:R APBDes')->name('index');
            Route::get('/create', [\App\Http\Controllers\MusrenbangController::class, 'create'])->middleware('permission:C APBDes')->name('create');
            Route::post('/', [\App\Http\Controllers\MusrenbangController::class, 'store'])->middleware('permission:C APBDes')->name('store');
            Route::get('/{musrenbang}', [\App\Http\Controllers\MusrenbangController::class, 'show'])->middleware('permission:R APBDes')->name('show');
            Route::post('/{musrenbang}/verify', [\App\Http\Controllers\MusrenbangController::class, 'verify'])->middleware('permission:U APBDes')->name('verify');
            Route::post('/{musrenbang}/review', [\App\Http\Controllers\MusrenbangController::class, 'review'])->middleware('permission:U APBDes')->name('review');
            Route::post('/{musrenbang}/approve', [\App\Http\Controllers\MusrenbangController::class, 'approve'])->middleware('permission:U APBDes')->name('approve');
            Route::post('/{musrenbang}/support', [\App\Http\Controllers\MusrenbangController::class, 'support'])->middleware('permission:C APBDes')->name('support');
        });
        
        // Pencairan Dana (Fase 2 - Keuangan)
        Route::prefix('pencairan-dana')->name('pencairan-dana.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PencairanDanaController::class, 'index'])->middleware('permission:R APBDes')->name('index');
            Route::get('/create', [\App\Http\Controllers\PencairanDanaController::class, 'create'])->middleware('permission:C APBDes')->name('create');
            Route::post('/', [\App\Http\Controllers\PencairanDanaController::class, 'store'])->middleware('permission:C APBDes')->name('store');
            Route::post('/{pencairanDana}/verify', [\App\Http\Controllers\PencairanDanaController::class, 'verify'])->middleware('permission:U APBDes')->name('verify');
            Route::post('/{pencairanDana}/approve', [\App\Http\Controllers\PencairanDanaController::class, 'approve'])->middleware('permission:U APBDes')->name('approve');
            Route::post('/{pencairanDana}/process', [\App\Http\Controllers\PencairanDanaController::class, 'process'])->middleware('permission:U APBDes')->name('process');
            Route::post('/{pencairanDana}/complete', [\App\Http\Controllers\PencairanDanaController::class, 'complete'])->middleware('permission:U APBDes')->name('complete');
        });
        
        // Belanja (Fase 2 - Pengadaan)
        Route::prefix('belanja')->name('belanja.')->group(function () {
            Route::get('/', [\App\Http\Controllers\BelanjaController::class, 'index'])->middleware('permission:R APBDes')->name('index');
            Route::get('/create', [\App\Http\Controllers\BelanjaController::class, 'create'])->middleware('permission:C APBDes')->name('create');
            Route::post('/', [\App\Http\Controllers\BelanjaController::class, 'store'])->middleware('permission:C APBDes')->name('store');
            Route::post('/{belanja}/approve', [\App\Http\Controllers\BelanjaController::class, 'approve'])->middleware('permission:U APBDes')->name('approve');
            Route::post('/{belanja}/deliver', [\App\Http\Controllers\BelanjaController::class, 'deliver'])->middleware('permission:U APBDes')->name('deliver');
            Route::post('/{belanja}/receive', [\App\Http\Controllers\BelanjaController::class, 'receive'])->middleware('permission:U APBDes')->name('receive');
            Route::post('/{belanja}/complete', [\App\Http\Controllers\BelanjaController::class, 'complete'])->middleware('permission:U APBDes')->name('complete');
        });

        // Pengaduan Dashboard (Enhanced Fase 2)
        Route::get('pengaduan/dashboard', [PengaduanController::class, 'index'])->name('pengaduan.dashboard');
        Route::get('pengaduan/{pengaduan}/detail', [PengaduanController::class, 'show'])->name('pengaduan.show');
        Route::post('pengaduan/{pengaduan}/proses', [PengaduanController::class, 'proses'])->name('pengaduan.proses');
        Route::post('pengaduan/{pengaduan}/selesai', [PengaduanController::class, 'selesai'])->name('pengaduan.selesai');
        Route::get('pengaduan/export', [PengaduanController::class, 'export'])->name('pengaduan.export');
        
        // Pengaduan Legacy Routes
        Route::get('pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::delete('pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');

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

        // Notifikasi Admin (tersedia untuk semua staff/role yang masuk area admin)
        Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/data', [\App\Http\Controllers\Admin\NotificationController::class, 'data'])->name('notifications.data');
        Route::post('notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAll'])->name('notifications.read-all');
        Route::delete('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');

        // Chat Warga (kotak masuk bersama untuk semua staff)
        Route::get('chat', [AdminChatController::class, 'index'])->name('chat.index');
        Route::get('chat/unread', [AdminChatController::class, 'unread'])->name('chat.unread');
        Route::get('chat/{chat}', [AdminChatController::class, 'show'])->name('chat.show');
        Route::get('chat/{chat}/data', [AdminChatController::class, 'data'])->name('chat.data');
        Route::post('chat/{chat}', [AdminChatController::class, 'kirim'])->name('chat.store');

        // QR & Link Wilayah (per RT/RW)
        Route::get('qr-links', [\App\Http\Controllers\Admin\QrCodeController::class, 'index'])->name('qr-links.index');
        Route::get('qr-links/create', [\App\Http\Controllers\Admin\QrCodeController::class, 'create'])->name('qr-links.create');
        Route::post('qr-links', [\App\Http\Controllers\Admin\QrCodeController::class, 'store'])->name('qr-links.store');
        Route::get('qr-links/{rtQrCode}/edit', [\App\Http\Controllers\Admin\QrCodeController::class, 'edit'])->name('qr-links.edit');
        Route::match(['put', 'patch'], 'qr-links/{rtQrCode}', [\App\Http\Controllers\Admin\QrCodeController::class, 'update'])->name('qr-links.update');
        Route::delete('qr-links/{rtQrCode}', [\App\Http\Controllers\Admin\QrCodeController::class, 'destroy'])->name('qr-links.destroy');
        Route::post('qr-links/{rtQrCode}/generate', [\App\Http\Controllers\Admin\QrCodeController::class, 'generate'])->name('qr-links.generate');
        Route::post('qr-links/{rt}/{rw}/generate', [\App\Http\Controllers\Admin\QrCodeController::class, 'generateByRtRw'])->name('qr-links.generateByRtRw');
        Route::get('qr-links/{rtQrCode}/download', [\App\Http\Controllers\Admin\QrCodeController::class, 'download'])->name('qr-links.download');
        Route::get('qr-links/cetak', [\App\Http\Controllers\Admin\QrCodeController::class, 'cetak'])->name('qr-links.cetak');
        Route::post('qr-links/{rt}/{rw}/status', [\App\Http\Controllers\Admin\QrCodeController::class, 'toggleStatus'])->name('qr-links.status');

        // Global Live Search API
        Route::get('search', [\App\Http\Controllers\Admin\SearchController::class, 'search'])->name('search');
    });

require __DIR__.'/auth.php';
