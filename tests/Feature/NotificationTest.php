<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Notification;
use App\Models\Pengaduan;
use App\Models\PengajuanSurat;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::firstOrCreate(
            ['email' => 'admin-notif@test.local'],
            [
                'name' => 'Admin Notif',
                'nik' => '3201010101010501',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$user->hasRole('Admin Desa')) {
            $user->assignRole('Admin Desa');
        }
        return $user;
    }

    public function test_pengaduan_qr_membuat_notifikasi_staff(): void
    {
        $this->admin();

        $this->post(route('warga.rt.createPengaduan', ['rt' => '01', 'rw' => '01']), [
            'nama' => 'Budi',
            'whatsapp' => '081234567890',
            'kategori' => 'jalan',
            'judul' => 'Jalan rusak',
            'deskripsi' => 'Jalan berlubang',
        ])->assertOk();

        $this->assertDatabaseHas('notifications', [
            'tipe' => 'pengaduan',
            'judul' => 'Pengaduan baru dari warga RT 01',
            'is_read' => false,
        ]);
    }

    public function test_pengajuan_surat_membuat_notifikasi_staff(): void
    {
        $this->admin();
        $jenis = JenisSurat::create(['kode' => 'SKU', 'nama' => 'Surat Keterangan Usaha', 'aktif' => true]);

        $this->post(route('warga.surat.store', $jenis), [
            'nama' => 'Warga Test',
            'nik' => '3201010101010111',
            'no_whatsapp' => '081234567890',
            'alamat' => 'Kp. Contoh',
            'keterangan' => 'Untuk keperluan bank',
        ])->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'tipe' => 'surat',
            'is_read' => false,
        ]);
    }

    public function test_admin_bisa_lihat_notifikasi_dan_menandai_dibaca(): void
    {
        $admin = $this->admin();

        Notification::buat($admin->id, [
            'judul' => 'Test Notifikasi',
            'pesan' => 'Pesan uji',
            'tipe' => 'sistem',
        ]);

        // Halaman index
        $this->actingAs($admin)
            ->get(route('admin.notifications.index'))
            ->assertOk()
            ->assertSee('Test Notifikasi');

        // Data JSON dropdown
        $this->actingAs($admin)
            ->get(route('admin.notifications.data'))
            ->assertOk()
            ->assertJsonPath('unread', 1);

        // Tandai dibaca
        $notification = Notification::first();
        $this->actingAs($admin)
            ->post(route('admin.notifications.read', $notification->id))
            ->assertOk();

        $this->assertTrue($notification->fresh()->is_read);

        // Tandai semua dibaca (buat satu lagi dulu)
        Notification::buat($admin->id, [
            'judul' => 'Notif Kedua',
            'pesan' => 'Pesan kedua',
            'tipe' => 'sistem',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.notifications.read-all'))
            ->assertOk();

        $this->assertEquals(0, Notification::belumDibaca()->count());
    }

    public function test_admin_tidak_bisa_akses_notifikasi_user_lain(): void
    {
        $this->admin();
        $other = User::firstOrCreate(
            ['email' => 'other-notif@test.local'],
            [
                'name' => 'User Lain',
                'nik' => '3201010101010502',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $other->assignRole('Admin Desa');

        $notif = Notification::buat($other->id, [
            'judul' => 'Punya user lain',
            'pesan' => 'Tidak boleh diakses',
            'tipe' => 'sistem',
        ]);

        $this->actingAs(User::where('email', 'admin-notif@test.local')->first())
            ->post(route('admin.notifications.read', $notif->id))
            ->assertStatus(404); // id notifikasi milik user lain -> tidak ditemukan
    }

    public function test_admin_tidak_bisa_hapus_notifikasi_user_lain(): void
    {
        $this->admin();
        $other = User::firstOrCreate(
            ['email' => 'other-notif@test.local'],
            [
                'name' => 'User Lain',
                'nik' => '3201010101010502',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $other->assignRole('Admin Desa');

        $notif = Notification::buat($other->id, [
            'judul' => 'Punya user lain',
            'pesan' => 'Tidak boleh dihapus',
            'tipe' => 'sistem',
        ]);

        $this->actingAs(User::where('email', 'admin-notif@test.local')->first())
            ->delete(route('admin.notifications.destroy', $notif->id))
            ->assertStatus(404);

        $this->assertDatabaseHas('notifications', ['id' => $notif->id]);
    }

    public function test_admin_bisa_hapus_notifikasi_sendiri(): void
    {
        $admin = $this->admin();

        $notif = Notification::buat($admin->id, [
            'judul' => 'Milik sendiri',
            'pesan' => 'Boleh dihapus',
            'tipe' => 'sistem',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.notifications.destroy', $notif->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('notifications', ['id' => $notif->id]);
    }
}
