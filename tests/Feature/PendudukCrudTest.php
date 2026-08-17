<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PendudukCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_admin_can_add_penduduk_via_ajax()
    {
        $admin = User::where('email', 'admin@puspamukti.local')->first();
        if (!$admin) {
            $admin = User::factory()->create();
            $admin->assignRole('Super Admin');
        }

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

        $response = $this->actingAs($admin)
            ->withHeaders([
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->postJson('/admin/penduduk', $data);

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
        $admin = User::where('email', 'admin@puspamukti.local')->first();
        if (!$admin) {
            $admin = User::factory()->create();
            $admin->assignRole('Super Admin');
        }

        // Invalid: missing NIK and Nama
        $response = $this->actingAs($admin)
            ->withHeaders([
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->postJson('/admin/penduduk', [
                'alamat' => 'Alamat tanpa NIK',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nik', 'nama']);
    }
}
