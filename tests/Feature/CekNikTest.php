<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CekNikTest extends TestCase
{
    use RefreshDatabase;

    public function test_cek_nik_returns_minimal_public_fields_only(): void
    {
        $penduduk = Penduduk::create([
            'nik' => '3201010101010199',
            'nama' => 'Data Rahasia',
            'alamat' => 'Alamat Pribadi',
            'rt' => '01',
            'rw' => '02',
            'status_perkawinan' => 'Kawin',
            'pekerjaan' => 'Petani',
            'pendidikan_terakhir' => 'SMA',
            'no_kk' => '3206060101010001',
        ]);

        $response = $this->getJson('/cek-nik/' . $penduduk->nik);

        $response->assertOk()
            ->assertJson([
                'found' => true,
                'data' => [
                    'nama' => 'Data Rahasia',
                    'alamat' => 'Alamat Pribadi',
                    'rt' => '01',
                    'rw' => '02',
                ],
            ]);

        $json = $response->json('data');

        // Field sensitif TIDAK boleh bocor
        $this->assertArrayNotHasKey('status_perkawinan', $json);
        $this->assertArrayNotHasKey('pekerjaan', $json);
        $this->assertArrayNotHasKey('pendidikan_terakhir', $json);
        $this->assertArrayNotHasKey('no_kk', $json);
    }

    public function test_cek_nik_returns_not_found_for_unknown_nik(): void
    {
        $this->getJson('/cek-nik/3201010101019999')->assertJson(['found' => false]);
    }

    public function test_cek_nik_rejects_non_numeric_or_wrong_length(): void
    {
        $this->getJson('/cek-nik/abc')->assertJson(['found' => false]);
        $this->getJson('/cek-nik/12345')->assertJson(['found' => false]);
    }
}
