<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\PengajuanSurat;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlurPenggunaTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::create([
            'name' => 'Admin Desa',
            'email' => 'admin-flow@test.local',
            'nik' => '3201010101010301',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('Admin Desa');
        return $user;
    }

    private function createUserWithRole(string $role): User
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::create([
            'name' => 'User ' . $role,
            'email' => strtolower(str_replace(' ', '', $role)) . '-flow@test.local',
            'nik' => '3201010101010' . substr((string) abs(crc32($role)), -3),
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);
        return $user;
    }

    public function test_dashboard_tidak_error_saat_pengajuan_surat_tanpa_login(): void
    {
        // Pengajuan surat publik (guest) -> user_id null
        $jenis = JenisSurat::create(['kode' => 'SKU', 'nama' => 'Surat Keterangan Usaha', 'aktif' => true]);
        PengajuanSurat::create([
            'user_id' => null,
            'jenis_surat_id' => $jenis->id,
            'status' => 'diajukan',
            'nama_pemohon' => 'Budi',
            'kode_tracking' => 'SRT-0001',
            'tanggal_diajukan' => now(),
        ]);

        $this->actingAs($this->admin())
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_dashboard_tidak_error_saat_pengaduan_qr_tanpa_login(): void
    {
        Pengaduan::create([
            'user_id' => null,
            'nama_pelapor' => 'Budi',
            'whatsapp' => '081234567890',
            'kategori' => 'jalan',
            'judul' => 'Jalan rusak',
            'deskripsi' => 'Lubang',
            'status' => 'diterima',
            'rt' => '01',
            'rw' => '01',
            'sumber_akses' => 'qr_rt',
        ]);

        $this->actingAs($this->admin())
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_pengajuan_surat_menyediakan_pemohon_name_accessor(): void
    {
        $jenis = JenisSurat::create(['kode' => 'SKU', 'nama' => 'Surat Keterangan Usaha', 'aktif' => true]);

        $pengajuan = PengajuanSurat::create([
            'user_id' => null,
            'jenis_surat_id' => $jenis->id,
            'status' => 'diajukan',
            'nama_pemohon' => 'Budi Santoso',
            'nik_pemohon' => '3201010101010111',
            'alamat_pemohon' => 'Kp. Contoh',
            'kode_tracking' => 'SRT-0002',
            'tanggal_diajukan' => now(),
        ]);

        // Accessor Eloquent dipakai view & PDF (sebelumnya null -> PDF blank)
        $this->assertSame('Budi Santoso', $pengajuan->pemohon_name);
        $this->assertSame('3201010101010111', $pengajuan->pemohon_nik);
        $this->assertSame('Kp. Contoh', $pengajuan->pemohon_alamat);
    }

    public function test_halaman_pengaduan_qr_tidak_error_untuk_guest(): void
    {
        $this->get(route('pengaduan.buat'))
            ->assertOk()
            ->assertSee('Pengaduan via QR Code');
    }

    public function test_login_warga_toleran_terhadap_spasi_dan_rt_tanpa_angka_nol(): void
    {
        // Penduduk RT '5' (bukan '05') + nama dengan spasi ganda
        Penduduk::create([
            'nik' => '3201010101010202',
            'nama' => 'Siti  Aminah',
            'rt' => '5',
            'rw' => '1',
            'alamat' => 'Kp. Contoh',
        ]);

        // Login via URL rt/05/rw/01 dengan nama spasi tunggal
        $this->post(route('warga.rt.login.authenticate', ['rt' => '05', 'rw' => '01']), [
            'nik' => '3201010101010202',
            'nama' => 'Siti Aminah',
        ])->assertRedirect();

        $this->assertAuthenticated();
    }

    public function test_halaman_laporan_keuangan_dapat_diakses_admin(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.apbdes.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard APBDes');
    }

    public function test_warga_tidak_bisa_mengakses_surat_rt_lain(): void
    {
        // Warga terdaftar di RT 01 RW 01
        $penduduk = Penduduk::create([
            'nik' => '3201010101010203',
            'nama' => 'Dewi',
            'rt' => '01',
            'rw' => '01',
            'alamat' => 'Kp. Cigalontang',
        ]);

        $this->post(route('warga.rt.login.authenticate', ['rt' => '01', 'rw' => '01']), [
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama,
        ]);

        // Akses surat RT 01 RW 01 -> boleh
        $this->get(route('warga.rt.surat.index', ['rt' => '01', 'rw' => '01']))
            ->assertOk();

        // Akses surat RT 02 RW 01 (bukan wilayahnya) -> ditolak
        $this->get(route('warga.rt.surat.index', ['rt' => '02', 'rw' => '01']))
            ->assertForbidden();
    }

    public function test_modul_musrenbang_dapat_diakses_admin(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.musrenbang.index'))
            ->assertOk();
    }

    public function test_modul_pencairan_dana_dapat_diakses_admin(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.pencairan-dana.index'))
            ->assertOk();
    }

    public function test_modul_belanja_dapat_diakses_admin(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.belanja.index'))
            ->assertOk();
    }

    public function test_alur_musrenbang_lengkap(): void
    {
        $admin = $this->admin();

        // 1. Usulan dibuat oleh Admin Desa -> status diusulkan
        $this->actingAs($admin)
            ->post(route('admin.musrenbang.store'), [
                'tahun' => date('Y'),
                'judul_kegiatan' => 'Pengerasan jalan lingkungan',
                'deskripsi_kegiatan' => 'Pembangunan jalan rabat beton',
                'jenis_kegiatan' => 'fisik',
                'estimasi_biaya' => 50000000,
                'sumber_dana' => 'APBDes',
                'prioritas' => 'tinggi',
                'tanggal_musrenbang' => now()->addWeek()->toDateString(),
            ])
            ->assertRedirect();

        $musrenbang = \App\Models\Musrenbang::first();
        $this->assertNotNull($musrenbang);
        $this->assertSame('diusulkan', $musrenbang->status_usulan);

        // Lihat detail usulan
        $this->actingAs($admin)
            ->get(route('admin.musrenbang.show', $musrenbang))
            ->assertOk()
            ->assertSee('Pengerasan jalan lingkungan');

        // 2-4. Workflow (verify -> review -> approve) dilakukan oleh user ber-izin U APBDes
        $sekdes = $this->createUserWithRole('Sekretaris Desa');
        $this->actingAs($sekdes)
            ->post(route('admin.musrenbang.verify', $musrenbang));
        $this->assertSame('diverifikasi', $musrenbang->fresh()->status_usulan);

        $this->actingAs($sekdes)
            ->post(route('admin.musrenbang.review', $musrenbang), [
                'hasil_musrenbang' => 'layak',
                'catatan_review' => 'Siap dibangun',
            ]);
        $this->assertSame('direview', $musrenbang->fresh()->status_usulan);
        $this->assertSame('layak', $musrenbang->fresh()->hasil_musrenbang);

        $this->actingAs($sekdes)
            ->post(route('admin.musrenbang.approve', $musrenbang), [
                'alokasi_anggaran' => 50000000,
            ]);
        $this->assertSame('disetujui', $musrenbang->fresh()->status_usulan);
        $this->assertEquals(50000000, (float) $musrenbang->fresh()->alokasi_anggaran);
    }

    public function test_admin_desa_tidak_bisa_verify_musrenbang(): void
    {
        $admin = $this->admin();

        $musrenbang = \App\Models\Musrenbang::create([
            'tahun' => date('Y'),
            'judul_kegiatan' => 'Perbaikan balai desa',
            'deskripsi_kegiatan' => 'Renovasi gedung',
            'jenis_kegiatan' => 'fisik',
            'estimasi_biaya' => 30000000,
            'sumber_dana' => 'APBDes',
            'prioritas' => 'rendah',
            'pengusul_id' => $admin->id,
            'status_usulan' => 'diusulkan',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.musrenbang.verify', $musrenbang))
            ->assertForbidden();
    }

    public function test_dukungan_warga_musrenbang(): void
    {
        $admin = $this->admin();

        $musrenbang = \App\Models\Musrenbang::create([
            'tahun' => date('Y'),
            'judul_kegiatan' => 'Pembangunan irigasi',
            'deskripsi_kegiatan' => 'Perbaikan saluran irigasi sawah',
            'jenis_kegiatan' => 'fisik',
            'estimasi_biaya' => 20000000,
            'sumber_dana' => 'APBDes',
            'prioritas' => 'sedang',
            'pengusul_id' => $admin->id,
            'status_usulan' => 'diusulkan',
        ]);

        // Support (suara dukungan)
        $this->actingAs($admin)
            ->post(route('admin.musrenbang.support', $musrenbang), [
                'tipe_suara' => 'dukung',
                'alasan' => 'Bermanfaat untuk warga',
            ]);

        $this->actingAs($admin)
            ->post(route('admin.musrenbang.support', $musrenbang), [
                'tipe_suara' => 'tolak',
                'alasan' => 'Ganti prioritas',
            ]);

        $this->assertSame(1, $musrenbang->fresh()->suara()->count());
        $this->assertDatabaseHas('musrenbang_suara', [
            'musrenbang_id' => $musrenbang->id,
            'tipe_suara' => 'tolak',
        ]);
    }
}
