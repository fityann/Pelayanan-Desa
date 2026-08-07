<?php

namespace Tests\Feature;

use App\Models\RtQrCode;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    private static int $userCounter = 0;

    private function admin(): User
    {
        $this->seed(RolePermissionSeeder::class);

        $n = ++static::$userCounter;

        $user = User::create([
            'name' => 'Admin Desa ' . $n,
            'email' => 'admin-desa-' . $n . '@test.local',
            'nik' => '3201010101010' . str_pad((string) $n, 3, '0', STR_PAD_LEFT),
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('Admin Desa');

        return $user;
    }

    public function test_index_page_lists_qr_links(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.qr-links.index'))
            ->assertOk();
    }

    public function test_create_form_renders(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.qr-links.create'))
            ->assertOk()
            ->assertSee('Tambah Wilayah Baru');
    }

    public function test_store_creates_new_rt_rw(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.qr-links.store'), [
                'rt' => '07',
                'rw' => '03',
                'nama_rt' => 'RT 07 RW 03',
                'status' => 'aktif',
            ])
            ->assertRedirect(route('admin.qr-links.index'));

        $this->assertDatabaseHas('rt_qr_codes', [
            'rt' => '07',
            'rw' => '03',
            'nama_rt' => 'RT 07 RW 03',
        ]);
    }

    public function test_store_rejects_duplicate_rt_rw(): void
    {
        RtQrCode::create(['rt' => '07', 'rw' => '03', 'nama_rt' => 'RT 07 RW 03', 'status' => 'aktif']);

        $this->actingAs($this->admin())
            ->post(route('admin.qr-links.store'), [
                'rt' => '07',
                'rw' => '03',
                'status' => 'aktif',
            ])
            ->assertSessionHasErrors('rt');
    }

    public function test_generate_creates_qr_image(): void
    {
        Storage::fake('public');

        $qrCode = RtQrCode::create([
            'rt' => '01',
            'rw' => '01',
            'nama_rt' => 'RT 01 RW 01',
            'status' => 'aktif',
            'created_by' => $this->admin()->id,
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.qr-links.generate', $qrCode))
            ->assertRedirect()
            ->assertSessionHas('success');

        $fresh = $qrCode->fresh();
        $this->assertNotNull($fresh->qr_code_path);
        $this->assertTrue(Storage::disk('public')->exists($fresh->qr_code_path));
        $this->assertNotNull($fresh->tanggal_generate);
    }

    public function test_generate_by_rt_rw_creates_record_and_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->post(route('admin.qr-links.generateByRtRw', ['rt' => '05', 'rw' => '02']))
            ->assertRedirect()
            ->assertSessionHas('success');

        $qrCode = RtQrCode::where('rt', '05')->where('rw', '02')->first();
        $this->assertNotNull($qrCode);
        $this->assertNotNull($qrCode->qr_code_path);
        $this->assertTrue(Storage::disk('public')->exists($qrCode->qr_code_path));
    }

    public function test_update_changes_wilayah(): void
    {
        $qrCode = RtQrCode::create(['rt' => '01', 'rw' => '01', 'nama_rt' => 'RT 01 RW 01', 'status' => 'aktif']);

        $this->actingAs($this->admin())
            ->put(route('admin.qr-links.update', $qrCode), [
                'rt' => '02',
                'rw' => '01',
                'nama_rt' => 'RT 02 RW 01',
                'deskripsi' => 'Update',
                'status' => 'nonaktif',
            ])
            ->assertRedirect(route('admin.qr-links.index'));

        $this->assertDatabaseHas('rt_qr_codes', [
            'id' => $qrCode->id,
            'rt' => '02',
            'rw' => '01',
            'status' => 'nonaktif',
        ]);
    }

    public function test_destroy_deletes_record(): void
    {
        Storage::fake('public');

        $qrCode = RtQrCode::create(['rt' => '03', 'rw' => '01', 'nama_rt' => 'RT 03 RW 01', 'status' => 'aktif']);
        $this->actingAs($this->admin())->post(route('admin.qr-links.generate', $qrCode));

        $this->actingAs($this->admin())
            ->delete(route('admin.qr-links.destroy', $qrCode))
            ->assertRedirect();

        $this->assertDatabaseMissing('rt_qr_codes', ['id' => $qrCode->id]);
    }

    public function test_cetak_page_renders(): void
    {
        Storage::fake('public');

        $qrCode = RtQrCode::create(['rt' => '01', 'rw' => '01', 'nama_rt' => 'RT 01 RW 01', 'status' => 'aktif']);
        $this->actingAs($this->admin())->post(route('admin.qr-links.generate', $qrCode));

        $this->actingAs($this->admin())
            ->get(route('admin.qr-links.cetak'))
            ->assertOk()
            ->assertSee('RT 01 / RW 01');
    }

    public function test_toggle_status_flips_status(): void
    {
        RtQrCode::create(['rt' => '01', 'rw' => '01', 'nama_rt' => 'RT 01 RW 01', 'status' => 'aktif']);

        $this->actingAs($this->admin())
            ->post(route('admin.qr-links.status', ['rt' => '01', 'rw' => '01']))
            ->assertRedirect();

        $this->assertDatabaseHas('rt_qr_codes', ['rt' => '01', 'rw' => '01', 'status' => 'nonaktif']);
    }
}
