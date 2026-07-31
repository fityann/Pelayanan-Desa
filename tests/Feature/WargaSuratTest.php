<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaSuratTest extends TestCase
{
    use RefreshDatabase;

    private function warga(): User
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Warga', 'guard_name' => 'web']);

        $user = User::create([
            'name' => 'Warga Test',
            'email' => 'wargatest@test.local',
            'nik' => '3201010101010111',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function jenisSurat(): JenisSurat
    {
        return JenisSurat::create([
            'kode' => 'SKU',
            'nama' => 'Surat Keterangan Usaha',
            'aktif' => true,
        ]);
    }

    public function test_warga_can_submit_pengajuan_surat(): void
    {
        $warga = $this->warga();
        $jenis = $this->jenisSurat();

        $response = $this->actingAs($warga)
            ->post(route('warga.surat.store', $jenis), [
                'keterangan' => 'Untuk keperluan rekening bank',
            ]);

        $response->assertRedirect();

        $pengajuan = PengajuanSurat::first();
        $this->assertNotNull($pengajuan);
        $this->assertSame('diajukan', $pengajuan->status);
        $this->assertSame(1, $pengajuan->riwayatStatus()->count());
    }

    public function test_warga_can_view_own_pengajuan_only(): void
    {
        $warga = $this->warga();
        $jenis = $this->jenisSurat();

        $this->actingAs($warga)->post(route('warga.surat.store', $jenis), [
            'keterangan' => 'keperluan test',
        ]);

        $pengajuan = PengajuanSurat::first();

        $this->actingAs($warga)
            ->get(route('warga.surat.status', $pengajuan))
            ->assertOk();

        // Warga lain tidak boleh lihat
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Warga', 'guard_name' => 'web']);
        $other = User::create([
            'name' => 'Warga Lain',
            'email' => 'wargalain@test.local',
            'nik' => '3201010101010112',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $other->assignRole($role);

        $this->actingAs($other)
            ->get(route('warga.surat.status', $pengajuan))
            ->assertForbidden();
    }

    public function test_pdf_not_available_before_approval(): void
    {
        $warga = $this->warga();
        $jenis = $this->jenisSurat();

        $this->actingAs($warga)->post(route('warga.surat.store', $jenis), [
            'keterangan' => 'keperluan test',
        ]);

        $pengajuan = PengajuanSurat::first();

        $this->actingAs($warga)
            ->get(route('warga.surat.pdf', $pengajuan))
            ->assertForbidden();
    }

    public function test_pdf_available_after_approval(): void
    {
        $warga = $this->warga();
        $jenis = $this->jenisSurat();

        $this->actingAs($warga)->post(route('warga.surat.store', $jenis), [
            'keterangan' => 'keperluan test',
        ]);

        $pengajuan = PengajuanSurat::first();
        $pengajuan->update([
            'status' => 'disetujui_kades',
            'nomor_surat' => 'SKU/001/07/2026',
            'approved_by' => $warga->id,
            'tanggal_disetujui' => now(),
        ]);

        $this->actingAs($warga)
            ->get(route('warga.surat.pdf', $pengajuan))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
