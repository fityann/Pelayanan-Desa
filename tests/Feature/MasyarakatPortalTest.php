<?php

namespace Tests\Feature;

use App\Models\Informasi;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\RtQrCode;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasyarakatPortalTest extends TestCase
{
    use RefreshDatabase;

    private function warga(): User
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::create([
            'name' => 'Warga Test',
            'email' => 'warga-test@test.local',
            'nik' => '3201010101010199',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('Warga');

        return $user;
    }

    private function pendudukRt01(): Penduduk
    {
        $this->seed(RolePermissionSeeder::class);

        return Penduduk::create([
            'nik' => '3201010101010201',
            'nama' => 'Budi Santoso',
            'rt' => '01',
            'rw' => '01',
            'alamat' => 'Kp. Cigalontang RT 01 RW 01',
        ]);
    }

    public function test_qr_landing_uses_masyarakat_theme(): void
    {
        RtQrCode::create(['rt' => '01', 'rw' => '01', 'nama_rt' => 'RT 01 RW 01', 'status' => 'aktif']);

        $response = $this->get(route('warga.rt.landing', ['rt' => '01', 'rw' => '01']));

        $response->assertOk();
        $response->assertSee('SILAPU');
        $response->assertSee('bottom-nav');   // bottom navigation khas mobile masyarakat
        $response->assertSee('RT 01 / RW 01');
    }

    public function test_info_desa_qr_uses_masyarakat_theme(): void
    {
        RtQrCode::create(['rt' => '02', 'rw' => '01', 'nama_rt' => 'RT 02 RW 01', 'status' => 'aktif']);

        $response = $this->get(route('warga.rt.info', ['rt' => '02', 'rw' => '01']));

        $response->assertOk();
        $response->assertSee('SILAPU');
        $response->assertSee('bottom-nav');
    }

    public function test_berita_difilter_berdasarkan_wilayah_rt_rw(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = $this->warga();

        // Berita khusus RT 01 RW 01
        Informasi::create([
            'judul' => 'Berita Khusus RT 01',
            'isi' => 'Khusus untuk warga RT 01',
            'published' => true,
            'kategori' => 'berita',
            'rt' => '01',
            'rw' => '01',
            'user_id' => $user->id,
            'published_at' => now(),
        ]);

        // Berita umum seluruh desa
        Informasi::create([
            'judul' => 'Berita Umum Desa',
            'isi' => 'Untuk semua warga',
            'published' => true,
            'kategori' => 'berita',
            'user_id' => $user->id,
            'published_at' => now(),
        ]);

        // Berita khusus RT 02 RW 01
        Informasi::create([
            'judul' => 'Berita Khusus RT 02',
            'isi' => 'Khusus untuk warga RT 02',
            'published' => true,
            'kategori' => 'berita',
            'rt' => '02',
            'rw' => '01',
            'user_id' => $user->id,
            'published_at' => now(),
        ]);

        // Landing RT 01 RW 01 -> tampil berita RT 01 + berita umum, TIDAK berita RT 02
        $this->get(route('warga.rt.landing', ['rt' => '01', 'rw' => '01']))
            ->assertOk()
            ->assertSee('Berita Khusus RT 01')
            ->assertSee('Berita Umum Desa')
            ->assertDontSee('Berita Khusus RT 02');

        // Landing RT 02 RW 01 -> tampil berita RT 02 + berita umum
        RtQrCode::create(['rt' => '02', 'rw' => '01', 'nama_rt' => 'RT 02 RW 01', 'status' => 'aktif']);
        $this->get(route('warga.rt.landing', ['rt' => '02', 'rw' => '01']))
            ->assertOk()
            ->assertSee('Berita Khusus RT 02')
            ->assertSee('Berita Umum Desa')
            ->assertDontSee('Berita Khusus RT 01');

        // Halaman info desa RT 01 -> tidak menampilkan berita RT 02
        $this->get(route('warga.rt.info', ['rt' => '01', 'rw' => '01']))
            ->assertOk()
            ->assertSee('Berita Khusus RT 01')
            ->assertDontSee('Berita Khusus RT 02');
    }

    public function test_apbdes_publik_uses_masyarakat_theme(): void
    {
        $response = $this->get(route('apbdes.publik'));

        $response->assertOk();
        $response->assertSee('SILAPU');
        $response->assertSee('bottom-nav');
        $response->assertSee('APBDes');
    }

    public function test_konteks_rt_rw_tetap_di_halaman_publik_setelah_buka_landing(): void
    {
        RtQrCode::create(['rt' => '01', 'rw' => '01', 'nama_rt' => 'RT 01 RW 01', 'status' => 'aktif']);

        // Warga membuka halaman landing RT terlebih dahulu (konteks tersimpan di session)
        $this->get(route('warga.rt.landing', ['rt' => '01', 'rw' => '01']))
            ->assertOk();

        // Saat pindah ke halaman publik APBDes, navbar tetap menampilkan konteks RT 01
        $response = $this->get(route('apbdes.publik'));
        $response->assertOk();
        $response->assertSee('RT 01 / RW 01');
        $response->assertSee('Beranda');
    }

    public function test_beranda_halaman_publik_mengarah_ke_landing_rt_saat_konteks_ada(): void
    {
        RtQrCode::create(['rt' => '03', 'rw' => '02', 'nama_rt' => 'RT 03 RW 02', 'status' => 'aktif']);

        // Buka halaman publik langsung dengan konteks di URL
        $this->get(route('informasi.publik', ['rt' => '03', 'rw' => '02']))
            ->assertOk();

        // Link Beranda di halaman publik harus mengarah ke landing RT 03 RW 02
        $landingUrl = route('warga.rt.landing', ['rt' => '03', 'rw' => '02']);
        $this->get(route('informasi.publik', ['rt' => '03', 'rw' => '02']))
            ->assertOk()
            ->assertSee('RT 03 / RW 02')
            ->assertSee(htmlspecialchars($landingUrl));
    }

    public function test_informasi_publik_uses_masyarakat_theme(): void
    {
        Informasi::create([
            'judul' => 'Berita Test',
            'isi' => 'Isi berita',
            'published' => true,
            'kategori' => 'berita',
            'user_id' => $this->warga()->id,
            'published_at' => now(),
        ]);

        $response = $this->get(route('informasi.publik'));

        $response->assertOk();
        $response->assertSee('SILAPU');
        $response->assertSee('bottom-nav');
        $response->assertSee('Berita Test');
    }

    public function test_surat_online_warga_tetap_pakai_panel_admin(): void
    {
        $response = $this->actingAs($this->warga())
            ->get(route('warga.surat.index'));

        $response->assertOk();
        $response->assertSee('SILAPU');
        $response->assertSee('sidebar-scrollbar');   // layout panel admin (sidebar)
        $response->assertDontSee('bottom-nav');       // bukan tema masyarakat
    }

    public function test_pengaduan_rt_sendirinya_bisa_dibuka(): void
    {
        $this->get(route('warga.rt.landing', ['rt' => '01', 'rw' => '01']))
            ->assertOk();

        // Cek bahwa pengaduan terkirim tercatat RT/RW yang benar
        $this->post(route('warga.rt.createPengaduan', ['rt' => '01', 'rw' => '01']), [
            'nama' => 'Budi',
            'whatsapp' => '081234567890',
            'kategori' => 'jalan',
            'judul' => 'Jalan rusak',
            'deskripsi' => 'Jalan berlubang',
        ])->assertOk();

        $this->assertDatabaseHas('pengaduans', [
            'rt' => '01',
            'rw' => '01',
            'nama_pelapor' => 'Budi',
            'sumber_akses' => 'qr_rt',
        ]);
    }

    public function test_halaman_login_warga_bisa_dibuka(): void
    {
        $response = $this->get(route('warga.rt.login', ['rt' => '01', 'rw' => '01']));

        $response->assertOk();
        $response->assertSee('Masuk Warga');
        $response->assertSee('NIK');
        $response->assertSee('Nama Lengkap');
        $response->assertSee('bottom-nav');
    }

    public function test_login_warga_berhasil_dengan_nik_nama_dari_data_penduduk(): void
    {
        $penduduk = $this->pendudukRt01();

        $response = $this->post(route('warga.rt.login.authenticate', ['rt' => '01', 'rw' => '01']), [
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama,
        ]);

        $response->assertRedirect(route('warga.rt.surat.index', ['rt' => '01', 'rw' => '01']));
        $this->assertAuthenticated();

        // Akun otomatis dibuat & terhubung ke penduduk, berperan Warga
        $user = $penduduk->fresh()->user;
        $this->assertNotNull($user);
        $this->assertSame($penduduk->nama, $user->name);
        $this->assertTrue($user->hasRole('Warga'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_warga_ditolak_jika_nik_belum_terdaftar(): void
    {
        $response = $this->from(route('warga.rt.login', ['rt' => '01', 'rw' => '01']))
            ->post(route('warga.rt.login.authenticate', ['rt' => '01', 'rw' => '01']), [
                'nik' => '4999999999999999',
                'nama' => 'Orang Asing',
            ]);

        $response->assertRedirect(route('warga.rt.login', ['rt' => '01', 'rw' => '01']));
        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    public function test_login_warga_ditolak_jika_nik_terdaftar_di_wilayah_lain(): void
    {
        // Penduduk terdaftar di RT 01 RW 01, tapi login lewat halaman RT 02 RW 01
        $penduduk = $this->pendudukRt01();

        $response = $this->from(route('warga.rt.login', ['rt' => '02', 'rw' => '01']))
            ->post(route('warga.rt.login.authenticate', ['rt' => '02', 'rw' => '01']), [
                'nik' => $penduduk->nik,
                'nama' => $penduduk->nama,
            ]);

        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    public function test_login_warga_ditolak_jika_nama_tidak_cocok(): void
    {
        $penduduk = $this->pendudukRt01();

        $response = $this->post(route('warga.rt.login.authenticate', ['rt' => '01', 'rw' => '01']), [
            'nik' => $penduduk->nik,
            'nama' => 'Nama Salah',
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertGuest();
    }

    public function test_halaman_surat_warga_memerlukan_login(): void
    {
        // Sebelum login -> diarahkan ke halaman login warga RT/RW
        $this->get(route('warga.rt.surat.index', ['rt' => '01', 'rw' => '01']))
            ->assertRedirect(route('warga.rt.login', ['rt' => '01', 'rw' => '01']));

        // Setelah login -> bisa membuka daftar surat
        $penduduk = $this->pendudukRt01();
        $this->post(route('warga.rt.login.authenticate', ['rt' => '01', 'rw' => '01']), [
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama,
        ]);

        $this->get(route('warga.rt.surat.index', ['rt' => '01', 'rw' => '01']))
            ->assertOk()
            ->assertSee('Surat Online');
    }

    public function test_warga_bisa_keluar_dari_sesi(): void
    {
        $penduduk = $this->pendudukRt01();
        $this->post(route('warga.rt.login.authenticate', ['rt' => '01', 'rw' => '01']), [
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama,
        ]);

        $this->assertAuthenticated();

        $this->post(route('warga.rt.logout', ['rt' => '01', 'rw' => '01']))
            ->assertRedirect(route('warga.rt.landing', ['rt' => '01', 'rw' => '01']));

        $this->assertGuest();
    }
}
