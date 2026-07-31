<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengajuanSuratSeeder extends Seeder
{
    public function run(): void
    {
        $warga1 = User::where('email', 'warga@puspamukti.local')->first();
        $warga2 = User::where('email', 'warga2@puspamukti.local')->first();
        $admin = User::where('email', 'admindesa@puspamukti.local')->first();
        $kades = User::where('email', 'kepaladesa@puspamukti.local')->first();

        $sku = JenisSurat::where('kode', 'SKU')->first();
        $skd = JenisSurat::where('kode', 'SKD')->first();
        $sktm = JenisSurat::where('kode', 'SKTM')->first();

        $pengajuan = [
            [
                'user_id' => $warga1?->id,
                'jenis_surat_id' => $sku?->id,
                'nomor_surat' => 'SKU/001/07/2026',
                'status' => 'selesai',
                'butuh_ttd_fisik' => true,
                'keterangan' => 'Untuk mengurus izin usaha warung sembako',
                'verified_by' => $admin?->id,
                'approved_by' => $kades?->id,
                'tanggal_diajukan' => now()->subDays(12),
                'tanggal_disetujui' => now()->subDays(10),
                'tanggal_ttd_fisik' => now()->subDays(9),
                'tanggal_diambil' => now()->subDays(8),
            ],
            [
                'user_id' => $warga2?->id,
                'jenis_surat_id' => $skd?->id,
                'nomor_surat' => 'SKD/002/07/2026',
                'status' => 'menunggu_ttd_fisik',
                'butuh_ttd_fisik' => true,
                'keterangan' => 'Untuk keperluan pembuatan kartu BPJS',
                'verified_by' => $admin?->id,
                'approved_by' => $kades?->id,
                'tanggal_diajukan' => now()->subDays(4),
                'tanggal_disetujui' => now()->subDays(2),
            ],
            [
                'user_id' => $warga1?->id,
                'jenis_surat_id' => $sktm?->id,
                'nomor_surat' => null,
                'status' => 'diverifikasi_admin',
                'butuh_ttd_fisik' => true,
                'keterangan' => 'Untuk pengajuan beasiswa anak sekolah',
                'verified_by' => $admin?->id,
                'tanggal_diajukan' => now()->subDays(1),
            ],
            [
                'user_id' => $warga2?->id,
                'jenis_surat_id' => $sku?->id,
                'nomor_surat' => null,
                'status' => 'diajukan',
                'butuh_ttd_fisik' => true,
                'keterangan' => 'Untuk keperluan pembuatan rekening bank',
                'tanggal_diajukan' => now()->subHours(3),
            ],
        ];

        foreach ($pengajuan as $data) {
            PengajuanSurat::firstOrCreate(
                ['user_id' => $data['user_id'], 'jenis_surat_id' => $data['jenis_surat_id'], 'keterangan' => $data['keterangan']],
                $data
            );
        }

        $this->command->info('Data pengajuan surat berhasil dibuat');
    }
}
