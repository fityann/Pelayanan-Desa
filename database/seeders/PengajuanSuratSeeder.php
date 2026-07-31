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
                'riwayat' => [
                    ['status' => 'diajukan', 'catatan' => 'Pengajuan diterima sistem. Menunggu verifikasi Admin Desa.', 'oleh' => $warga1?->id],
                    ['status' => 'diverifikasi_admin', 'catatan' => 'Berkas lengkap, diteruskan ke Kepala Desa untuk approval.', 'oleh' => $admin?->id],
                    ['status' => 'disetujui_kades', 'catatan' => 'Surat disetujui. Nomor: SKU/001/07/2026', 'oleh' => $kades?->id],
                    ['status' => 'menunggu_ttd_fisik', 'catatan' => 'Draft PDF siap cetak.', 'oleh' => $kades?->id],
                    ['status' => 'selesai', 'catatan' => 'Surat telah ditandatangani dan siap diambil warga.', 'oleh' => $admin?->id],
                ],
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
                'riwayat' => [
                    ['status' => 'diajukan', 'catatan' => 'Pengajuan diterima sistem. Menunggu verifikasi Admin Desa.', 'oleh' => $warga2?->id],
                    ['status' => 'diverifikasi_admin', 'catatan' => 'Berkas lengkap, diteruskan ke Kepala Desa untuk approval.', 'oleh' => $admin?->id],
                    ['status' => 'disetujui_kades', 'catatan' => 'Surat disetujui. Nomor: SKD/002/07/2026', 'oleh' => $kades?->id],
                    ['status' => 'menunggu_ttd_fisik', 'catatan' => 'Draft PDF siap cetak. Menunggu tanda tangan fisik.', 'oleh' => $kades?->id],
                ],
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
                'riwayat' => [
                    ['status' => 'diajukan', 'catatan' => 'Pengajuan diterima sistem. Menunggu verifikasi Admin Desa.', 'oleh' => $warga1?->id],
                    ['status' => 'diverifikasi_admin', 'catatan' => 'Berkas lengkap, diteruskan ke Kepala Desa untuk approval.', 'oleh' => $admin?->id],
                ],
            ],
            [
                'user_id' => $warga2?->id,
                'jenis_surat_id' => $sku?->id,
                'nomor_surat' => null,
                'status' => 'diajukan',
                'butuh_ttd_fisik' => true,
                'keterangan' => 'Untuk keperluan pembuatan rekening bank',
                'tanggal_diajukan' => now()->subHours(3),
                'riwayat' => [
                    ['status' => 'diajukan', 'catatan' => 'Pengajuan diterima sistem. Menunggu verifikasi Admin Desa.', 'oleh' => $warga2?->id],
                ],
            ],
        ];

        foreach ($pengajuan as $data) {
            $riwayat = $data['riwayat'] ?? [];
            unset($data['riwayat']);

            $pengajuan = PengajuanSurat::firstOrCreate(
                ['user_id' => $data['user_id'], 'jenis_surat_id' => $data['jenis_surat_id'], 'keterangan' => $data['keterangan']],
                $data
            );

            if ($pengajuan->riwayatStatus()->count() === 0) {
                foreach ($riwayat as $i => $r) {
                    $pengajuan->riwayatStatus()->create([
                        'status' => $r['status'],
                        'catatan' => $r['catatan'] ?? null,
                        'oleh_user_id' => $r['oleh'] ?? null,
                        'created_at' => ($pengajuan->tanggal_diajukan ?? now())->addHours($i * 8),
                    ]);
                }
            }
        }

        $this->command->info('Data pengajuan surat berhasil dibuat');
    }
}
