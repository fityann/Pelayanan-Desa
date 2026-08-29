<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private static int $nikCounter = 0;

    protected function createUserWithRole(string $role): User
    {
        // Sinkronkan role + permission sesuai definisi produksi
        $this->seed(RolePermissionSeeder::class);

        $user = User::create([
            'name' => 'User ' . $role,
            'email' => strtolower(str_replace(' ', '', $role)) . '@test.local',
            'nik' => '3201010101010' . str_pad((string) ++static::$nikCounter, 3, '0', STR_PAD_LEFT),
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($role);

        return $user;
    }

    public function test_warga_cannot_access_admin_routes(): void
    {
        $warga = $this->createUserWithRole('Warga');

        $this->actingAs($warga)
            ->get(route('admin.penduduk.index'))
            ->assertForbidden();

        $this->actingAs($warga)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($warga)
            ->get(route('admin.apbdes.create'))
            ->assertForbidden();
    }

    public function test_staff_can_access_admin_routes(): void
    {
        $admin = $this->createUserWithRole('Admin Desa');

        $this->actingAs($admin)
            ->get(route('admin.penduduk.index'))
            ->assertOk();
    }

    public function test_non_admin_roles_cannot_access_user_management(): void
    {
        $bendahara = $this->createUserWithRole('Bendahara');

        $this->actingAs($bendahara)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_permission_crud_is_enforced_on_routes(): void
    {
        // Manajemen User & Role & Permission hanya dimiliki Super Admin / Kepala Desa.
        $admin = $this->createUserWithRole('Admin Desa');
        $kades = $this->createUserWithRole('Kepala Desa');

        // Admin Desa tidak punya 'R Role & Permission' -> ditolak
        $this->actingAs($admin)
            ->get(route('admin.roles.index'))
            ->assertForbidden();

        // Admin Desa tidak punya 'R Manajemen User' -> ditolak
        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        // Kepala Desa punya izin tersebut -> boleh
        $this->actingAs($kades)
            ->get(route('admin.roles.index'))
            ->assertOk();

        $this->actingAs($kades)
            ->get(route('admin.users.index'))
            ->assertOk();
    }

    public function test_roles_page_works_with_seeded_permissions(): void
    {
        $kades = $this->createUserWithRole('Kepala Desa');

        $response = $this->actingAs($kades)
            ->get(route('admin.roles.index'));

        $response->assertOk();
        $response->assertSee('CR');
        $response->assertSee('UD');
    }

    public function test_approve_surat_mengikuti_permission_pengajuan_surat(): void
    {
        $admin = $this->createUserWithRole('Admin Desa');

        $jenis = \App\Models\JenisSurat::create([
            'kode' => 'SKU',
            'nama' => 'Surat Keterangan Usaha',
        ]);

        $pengajuan = \App\Models\PengajuanSurat::create([
            'user_id' => $admin->id,
            'jenis_surat_id' => $jenis->id,
            'status' => 'diverifikasi_admin',
            'butuh_ttd_fisik' => true,
        ]);

        // Admin Desa punya 'U Pengajuan Surat' -> boleh approve
        $this->actingAs($admin)
            ->post(route('admin.surat.approve', $pengajuan))
            ->assertRedirect(route('admin.surat.pengajuan'));

        $this->assertSame('menunggu_ttd_fisik', $pengajuan->fresh()->status);
        $this->assertNotNull($pengajuan->fresh()->nomor_surat);

        // Cabut izin approve -> akses ditolak 403
        $role = Role::findByName('Admin Desa');
        $role->revokePermissionTo('U Pengajuan Surat');

        $pengajuan2 = \App\Models\PengajuanSurat::create([
            'user_id' => $admin->id,
            'jenis_surat_id' => $jenis->id,
            'status' => 'diverifikasi_admin',
            'butuh_ttd_fisik' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.surat.approve', $pengajuan2))
            ->assertForbidden();
    }

    public function test_revoking_permission_revokes_access(): void
    {
        $admin = $this->createUserWithRole('Admin Desa');
        $role = Role::findByName('Admin Desa');

        // Cabut izin membuat penduduk
        $role->revokePermissionTo('C Penduduk');

        $this->actingAs($admin)
            ->get(route('admin.penduduk.create'))
            ->assertForbidden();
    }
}
