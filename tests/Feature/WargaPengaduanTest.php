<?php

namespace Tests\Feature;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaPengaduanTest extends TestCase
{
    use RefreshDatabase;

    public function test_warga_can_submit_pengaduan_via_qr_url(): void
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Warga', 'guard_name' => 'web']);

        $warga = User::create([
            'name' => 'Warga Test',
            'email' => 'wargatest@test.local',
            'nik' => '3201010101010111',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $warga->assignRole($role);

        $this->actingAs($warga)
            ->get(route('pengaduan.buat', ['qr' => 1]))
            ->assertOk();

        $response = $this->actingAs($warga)
            ->post(route('pengaduan.store', ['qr' => 1]), [
                'kategori' => 'Infrastruktur',
                'judul' => 'Jalan rusak',
                'deskripsi' => 'Jalan berlubang di depan rumah',
            ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('pengaduans', [
            'user_id' => $warga->id,
            'kategori' => 'Infrastruktur',
            'judul' => 'Jalan rusak',
            'status' => 'diterima',
            'sumber_akses' => 'qr_code',
        ]);
    }
}
