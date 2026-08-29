<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendudukCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@puspamukti.local',
            'nik' => '3201010101010101',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('Super Admin');

        return $user;
    }

    public function test_admin_can_add_penduduk_via_ajax()
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $nik = '320101' . rand(1000000000, 9999999999);
        $data = [
            'nik' => $nik,
            'nama' => 'Budi Santoso Otomatis',
            'no_kk' => '320101' . rand(1000000000, 9999999999),
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Tasikmalaya',
            'tanggal_lahir' => '1990-08-17',
            'alamat' => 'Kp. Sukaluyu RT 01 RW 01',
            'rt' => '01',
            'rw' => '01',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'pendidikan_terakhir' => 'S1',
            'pekerjaan' => 'PNS',
            'kewarganegaraan' => 'WNI',
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->postJson('/admin/penduduk', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Data penduduk berhasil ditambahkan'
        ]);

        $this->assertDatabaseHas('penduduk', [
            'nik' => $nik,
            'nama' => 'Budi Santoso Otomatis',
        ]);
    }

    public function test_admin_validation_error_returns_json()
    {
        $this->actingAs($this->admin());

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->postJson('/admin/penduduk', [
            'alamat' => 'Alamat tanpa NIK',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nik', 'nama']);
    }
}