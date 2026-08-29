<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaSuratTest extends TestCase
{
    use RefreshDatabase;

    private function pengajuanData(): array
    {
        return [
            'nama' => 'Warga Test',
            'nik' => '3201010101010111',
            'no_whatsapp' => '081234567890',
            'alamat' => 'Kp. Contoh RT 01/RW 01',
            'keterangan' => 'Untuk keperluan rekening bank',
        ];
    }

    private function jenisSurat(): JenisSurat
    {
        return JenisSurat::create([
            'kode' => 'SKU',
            'nama' => 'Surat Keterangan Usaha',
            'aktif' => true,
        ]);
    }

    private function warga(): User
    {
        $penduduk = Penduduk::create([
            'nik' => '3201010101010111',
            'nama' => 'Warga Test',
            'rt' => '01',
            'rw' => '01',
            'alamat' => 'Kp. Contoh RT 01/RW 01',
        ]);

        return User::create([
            'name' => $penduduk->nama,
            'email' => 'warga-surat-tes@test.local',
            'nik' => $penduduk->nik,
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'rt' => $penduduk->rt,
            'rw' => $penduduk->rw,
        ]);
    }

    public function test_warga_bisa_mengirim_pengajuan_surat(): void
    {
        $jenis = $this->jenisSurat();
        $warga = $this->warga();

        $response = $this->actingAs($warga, 'warga')
            ->post(route('warga.rt.surat.store', ['rt' => '01', 'jenisSurat' => $jenis]), $this->pengajuanData());

        $response->assertRedirect();

        $pengajuan = PengajuanSurat::first();
        $this->assertNotNull($pengajuan);
        $this->assertSame('diajukan', $pengajuan->status);
        $this->assertSame('Warga Test', $pengajuan->nama_pemohon);
        $this->assertNotNull($pengajuan->kode_tracking);
        $this->assertSame(1, $pengajuan->riwayatStatus()->count());
    }

    public function test_warga_dapat_melihat_status_via_kode_tracking(): void
    {
        $jenis = $this->jenisSurat();
        $warga = $this->warga();

        $this->actingAs($warga, 'warga')
            ->post(route('warga.rt.surat.store', ['rt' => '01', 'jenisSurat' => $jenis]), $this->pengajuanData());

        $pengajuan = PengajuanSurat::first();

        $this->actingAs($warga, 'warga')
            ->get(route('warga.rt.surat.status', ['rt' => '01', 'kode' => $pengajuan->kode_tracking]))
            ->assertOk();
    }

    public function test_status_cek_dapat_dibuka_guest(): void
    {
        $jenis = $this->jenisSurat();
        $warga = $this->warga();

        $this->actingAs($warga, 'warga')
            ->post(route('warga.rt.surat.store', ['rt' => '01', 'jenisSurat' => $jenis]), $this->pengajuanData());

        $pengajuan = PengajuanSurat::first();

        $this->get(route('warga.surat.status', $pengajuan->kode_tracking))
            ->assertOk();
    }

    public function test_pdf_not_available_before_approval(): void
    {
        $jenis = $this->jenisSurat();
        $warga = $this->warga();

        $this->actingAs($warga, 'warga')
            ->post(route('warga.rt.surat.store', ['rt' => '01', 'jenisSurat' => $jenis]), $this->pengajuanData());

        $pengajuan = PengajuanSurat::first();

        $this->actingAs($warga, 'warga')
            ->get(route('warga.rt.surat.pdf', ['rt' => '01', 'kode' => $pengajuan->kode_tracking]))
            ->assertForbidden();
    }

    public function test_pdf_available_after_approval(): void
    {
        $jenis = $this->jenisSurat();
        $warga = $this->warga();

        $this->actingAs($warga, 'warga')
            ->post(route('warga.rt.surat.store', ['rt' => '01', 'jenisSurat' => $jenis]), $this->pengajuanData());

        $pengajuan = PengajuanSurat::first();
        $pengajuan->update([
            'status' => 'disetujui_kades',
            'nomor_surat' => 'SKU/001/07/2026',
            'tanggal_disetujui' => now(),
        ]);

        $this->actingAs($warga, 'warga')
            ->get(route('warga.rt.surat.pdf', ['rt' => '01', 'kode' => $pengajuan->kode_tracking]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}