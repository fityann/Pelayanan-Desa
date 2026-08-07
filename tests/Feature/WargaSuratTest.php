<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
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

    public function test_guest_can_submit_pengajuan_surat_without_login(): void
    {
        $jenis = $this->jenisSurat();

        $response = $this->post(route('warga.surat.store', $jenis), $this->pengajuanData());

        $response->assertRedirect();

        $pengajuan = PengajuanSurat::first();
        $this->assertNotNull($pengajuan);
        $this->assertSame('diajukan', $pengajuan->status);
        $this->assertNull($pengajuan->user_id);
        $this->assertSame('Warga Test', $pengajuan->nama_pemohon);
        $this->assertNotNull($pengajuan->kode_tracking);
        $this->assertSame(1, $pengajuan->riwayatStatus()->count());
    }

    public function test_guest_can_check_status_via_kode_tracking(): void
    {
        $jenis = $this->jenisSurat();

        $this->post(route('warga.surat.store', $jenis), $this->pengajuanData());

        $pengajuan = PengajuanSurat::first();

        $this->get(route('warga.surat.status', $pengajuan->kode_tracking))
            ->assertOk();
    }

    public function test_guest_can_submit_and_get_kode(): void
    {
        $jenis = $this->jenisSurat();

        $response = $this->post(route('warga.surat.store', $jenis), $this->pengajuanData());

        $pengajuan = PengajuanSurat::first();
        $response->assertRedirect(route('warga.surat.status', $pengajuan->kode_tracking));
    }

    public function test_pdf_not_available_before_approval(): void
    {
        $jenis = $this->jenisSurat();

        $this->post(route('warga.surat.store', $jenis), $this->pengajuanData());

        $pengajuan = PengajuanSurat::first();

        $this->get(route('warga.surat.pdf', $pengajuan->kode_tracking))
            ->assertForbidden();
    }

    public function test_pdf_available_after_approval(): void
    {
        $jenis = $this->jenisSurat();

        $this->post(route('warga.surat.store', $jenis), $this->pengajuanData());

        $pengajuan = PengajuanSurat::first();
        $pengajuan->update([
            'status' => 'disetujui_kades',
            'nomor_surat' => 'SKU/001/07/2026',
            'tanggal_disetujui' => now(),
        ]);

        $this->get(route('warga.surat.pdf', $pengajuan->kode_tracking))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}